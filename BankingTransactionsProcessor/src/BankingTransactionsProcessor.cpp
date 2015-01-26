#include <stdlib.h>
#include <stdio.h>
#include <iostream>
#include <string.h>
#include <mysql_connection.h>
#include <driver.h>
#include <exception.h>
#include <resultset.h>
#include <statement.h>
#include <vector>
#include <sstream>

#include "bankingtypes.h"
#include <mcrypt.h>

#include "crypto.h"
#include "AESEncryptionExample.h"

/* MySQL Connector/C++ specific headers */
#include <cppconn/driver.h>
#include <cppconn/connection.h>
#include <cppconn/statement.h>
#include <cppconn/prepared_statement.h>
#include <cppconn/resultset.h>
#include <cppconn/metadata.h>
#include <cppconn/resultset_metadata.h>
#include <cppconn/exception.h>
#include <cppconn/warning.h>

using namespace sql;
using namespace std;

#define DB_SERVER "server"
#define DB_USER "user"
#define DB_PASS "password"
#define DB_DATABASE "database"

#define DEBUG 0

char * dbHost = DB_SERVER;
char * dbUser = DB_USER;
char * dbPass = DB_PASS;
char * dbName = DB_DATABASE;

sql::Connection* initialize_connetion();
void check_sum(Transaction * t, sql::Connection *con) throw(sql::SQLException);
void insert_transaction_sender(Transaction * t, int confirmed, sql::Connection *con) throw(sql::SQLException);
void insert_transaction_receiver(Transaction * t, int confirmed, sql::Connection *con) throw(sql::SQLException);
void update_balance(Transaction * t, sql::Connection *con) throw(sql::SQLException);
void diactivate_transaction_code(Transaction * t, sql::Connection *con) throw(sql::SQLException);
void debug_db(char const name[], sql::Connection *con) throw(sql::SQLException);
char * extract_user_aes_key(sql::Connection *con) throw(sql::SQLException);
int checkSCSTan( Transaction * t, sql::Connection *con);
void encode(std::string& data);
void diactivate_scs_transaction_code(Transaction * t, sql::Connection *con) throw(sql::SQLException);

std::vector<std::string> split(const std::string &s, char delim);
std::vector<std::string> &split(const std::string &s, char delim, std::vector<std::string> &elems);
std::string intToString(int number);

int user_id = 0;
char * user_key ;

int main(int argc, char ** args) {

	if (argc < 7) {
		printf("usage: owner_id transactions_file dbServer dbUser dbPassword dbName \n");
		exit(-1);
	}
	if (argc > 7) {
		printf("too many arguments\n");
		exit(-1);
	}

	if(strlen(args[3]) > 0){
			dbHost = args[3];
			dbUser = args[4];
			dbPass = args[5];
			dbName = args[6];
		}

	sql::Connection *con = initialize_connetion();
	if( con == NULL){
		printf("no connection to db");
		exit(-1);
	}

	//parameters are sent by our own php applications.
	//it is considered as trusted
	user_id = atoi(args[1]);
	if(user_id < 1){
		cout<< "invalid user" << endl;
		exit(-1);
	}
	char * transactions_file = args[2];

	std::vector<Transaction *> transactions = load_transactions(transactions_file);
	try {
		process_transactions(transactions, con);
	} catch (sql::SQLException &e) {
		Statement *stmt = con -> createStatement();
		stmt -> execute ("ROLLBACK;");
		delete stmt;

		cout << "# Please check transaction code, account number or sufficient balance." << endl;
		if(DEBUG == 1)
		{
			cout << "# ERR: " << e.what();
			cout << " (MySQL error code: " << e.getErrorCode();
			cout << ", SQLState: " << e.getSQLState() << " )" ;
		}
		cout  << endl;
	}

	cout << endl ;
	con->close();
	delete con;

	return 0;
}


int checkSCSTan( Transaction * t, sql::Connection *con){
	char* encryptedTan = t->tan;
	///retrivea the client key from db
	char* mykey =  extract_user_aes_key(con);//"5UvSoqvtVVtrV2ZW";
	///try to decrypt
	char* decrypted = decryptSCS( mykey,  encryptedTan);
	if(DEBUG == 1){
		cout << "mykey:"<< mykey << endl;
		cout << "encryptedTan:"<< encryptedTan << endl;
		cout << "decr:"<< decrypted << endl;
	}
		///here is the tan that you received
	std::vector<std::string> elements = split(decrypted, ';');
	string destination;
	string amount;
	string date;
	if (elements.size() == 3){
		destination = elements.at(0);
		amount = elements.at(1);
		date = elements.at(2);
		if(DEBUG == 1){
			cout<<destination <<" "<<amount<<" "<<date<<endl;
		}
	}

	int amountInt = atoi(amount.c_str());
	string amountFile = intToString(t->amount);

	if (amountFile.compare(amount) != 0){
		if(DEBUG == 1){
			cout<<"amount_err "<< amountFile << " " <<amount<<endl;
			cout<<"amount_err "<< amountFile.size() << " " <<amount.size()<<endl;
			return 0;
		}
	}

	int destinationInt = atoi(destination.c_str());
	string dstFile = intToString(t->dst_acc);
	if (destinationInt != t->dst_acc){
		if(DEBUG == 1){
			cout<<"dst_err "<< destinationInt << " " <<t->dst_acc<<endl;
			return 0;
		}
	}

	//TODO: check time

	return 1;
}

std::vector<Transaction *> load_transactions(char filename[]) {
	FILE * fp;
	char * line = NULL;
	size_t len = 200;
	ssize_t read;
	std::vector<Transaction *> transactions(1);

	fp = fopen(filename, "r");
	if (fp == NULL) {
		printf("parser failed to open file\n");
		exit(-1);
	}

	//we limit the amount of allowed transactions in a file
	int MAX_TRANSACTIONS = 100;
	int transactionIndex = 0 ;

	while ((read = getline(&line, &len, fp)) != -1) {
		if (line[0] == 0) {
			continue;
		}
		//filter out comments
		if (line[0] == '#') {
			continue;
		}
		//filter out short lines
		if (strlen(line) < 5) {
			continue;
		}

		if(transactionIndex < MAX_TRANSACTIONS){
			Transaction * t = convert_transaction(line);
			if(t==NULL){
				cout << "invalid trasaction - trasaction " << transactionIndex+1 <<endl;
				exit(-1);
			}
			transactions.push_back(t);
			transactionIndex ++ ;
		}
	}

	fclose(fp);
	if (line)
		free(line);

	return transactions;
}



Transaction * convert_transaction(char * line) {

	char delimiters[] = ",";
	char * tan = strtok(line, delimiters);
	char * src_acc = strtok(NULL, delimiters);
	char * dst_acc = strtok(NULL, delimiters);
	char * amount = strtok(NULL, delimiters);
	char * description = strtok(NULL, delimiters);

	//printf("tan %s\n", tan);
	//printf("src_acc %s\n", src_acc);
	//printf("dst_acc %s\n", dst_acc);
	//printf("amount %s\n", amount);
	//printf("description %s\n", description);
	if(tan == NULL)
		return NULL;
	if(src_acc == NULL)
		return NULL;
	if(dst_acc == NULL)
		return NULL;
	if(amount == NULL)
		return NULL;

	Transaction * t = (Transaction*) malloc(sizeof(*t));
	if(t==NULL){
		return NULL;
	}
	strncpy(t->tan, tan, 200);
	t->src_acc = strtol (src_acc,NULL,10);
	t->dst_acc = strtol(dst_acc,NULL,10);
	t->amount = strtol(amount,NULL,10);
	if(t->amount < 0){
		cout<<"amount is not allowed to be negative"<<endl;
		exit(-1);
	}
	if(strlen(t->tan)>20){
		t->type = 1; //scs
	}else {
		t->type = 0; //normal
	}
	//force last character to end of string
	description[250] = '\0';
	string des = string(description);
	//escape "interesting" characters
	encode(des);

	strncpy(t->description, des.c_str(), 251);

	if(DEBUG == 1)
	{
		printf("loaded: %s %d %d %d %s type %d\n", t->tan, t->src_acc, t->dst_acc,
			t->amount, t->description, t->type);
	}
	return t;
}

sql::Connection * initialize_connetion() {

	sql::Connection *con;
	sql::Driver *driver;

	driver = get_driver_instance();
	if(DEBUG==1){
		cout<<"DB: " <<dbHost <<" "<< dbUser <<" "<<dbPass<< " "<< dbName <<endl;
	}
	con = driver->connect(dbHost, dbUser, dbPass);
	/* Connect to the MySQL test database */
	con->setSchema(dbName);

	/* disable the autocommit */
	//con -> setAutoCommit(0);

	return con;
}

char * extract_user_aes_key(sql::Connection *con) throw(sql::SQLException) {
	PreparedStatement *prep_stmt;
	sql::ResultSet *res ;

	char extractKeySql[] = "SELECT u_akey FROM users WHERE u_id = ?;" ;
	prep_stmt = con->prepareStatement(extractKeySql);
	prep_stmt->setInt(1, user_id); //a_number
	res = prep_stmt->executeQuery();

	string k ;
	 if (res->next()) {
	    /* Access column fata by numeric offset, 1 is the first column */
	    k = res->getString(1);
	  }

	  const char * key = k.c_str();
	  char * buffer = (char*) calloc(51, sizeof(char*));
	  if(buffer == NULL){
	 		  exit(-1);
	  }

	  //cout<<"buffer " << key << " size "<<strlen(key) << endl;
	  if(strlen(key) < 51)
		  strncpy(buffer, key, strlen(key));
	  buffer[50] = 0;

	  delete res;
	  delete prep_stmt;
	  return buffer;
}

int process_transactions(std::vector<Transaction *> transactions, sql::Connection *con) throw(sql::SQLException) {
	PreparedStatement *prep_stmt;
	Statement *stmt = con -> createStatement();

	int totalTransactions = 0;
	// i use this because somehow there are elements set on NULL
	for (unsigned i=0; i<transactions.size(); i++){
			Transaction * t = transactions.at(i) ;
			if(t == NULL)
				continue;
			totalTransactions++;
	}
	if(DEBUG == 1)
	{
		cout << "start>> transactions to process: " << totalTransactions <<endl;
	}
	stmt -> execute ("START TRANSACTION;");

	char totalTransactionsSql[] = "SET @totalTransactions = ?;";
	if(DEBUG == 1)
	{
		cout << totalTransactionsSql << endl;
	}

	prep_stmt = con->prepareStatement(totalTransactionsSql);
	prep_stmt->setInt(1, totalTransactions); //a_number
	prep_stmt->execute();
    debug_db("@totalTransactions", con);
	stmt -> execute ("SET @validTransactionsCounter = 0 ;");

	int processed_transactions = 0;
	for (unsigned i=0; i<transactions.size(); i++){
		Transaction * t = transactions.at(i) ;
		if(t == NULL)
			continue;
		if (0==checkSCSTan(t,con)){
			cout <<"invalid entry"<<endl;
			return 0;
		}
		if(DEBUG == 1)
		{
			cout << "processing [tan] >> " << t->tan
						<< t->src_acc <<" "<<t->dst_acc << " " << t->amount <<" "
						<< user_id << " " << t->description
						<< endl;
		}
		int confirmed = 1;
		int value = t->amount;
		if(value > 10000)
			confirmed = 0;

		check_sum(t, con);
		insert_transaction_sender(t, confirmed, con);
		insert_transaction_receiver(t, confirmed, con);
		update_balance(t, con);
		if(t->type==0)
			diactivate_transaction_code(t, con);
		else
			diactivate_scs_transaction_code(t,con);

		processed_transactions ++;
	}
	//stmt -> execute ("COMMIT;");
	int performed_operations = 0;

	//commit OR rollback ar done inside the procedure
	sql::ResultSet  *res = stmt -> executeQuery ("CALL process_transaction()");
	while (res->next()) {
		if(DEBUG == 1)
		{
			cout << "\t... MySQL says: ";
		}
	    /* Access column fata by numeric offset, 1 is the first column */
	    performed_operations = res->getInt(1);
	    cout << performed_operations << endl;
	}

	delete res;
	delete stmt;
	delete prep_stmt;
	cout << "----------" <<endl;
	if (performed_operations == totalTransactions){
		cout << "SUCCESS: performed transactions: " <<  totalTransactions << endl;
	}
	else {
		cout << "2 operations / file entry " << endl;
		cout << "performed operations: " << performed_operations << " from total " << totalTransactions * 2 <<endl;
		cout << "ERROR: transaction processing ROLLED BACK" <<endl;
		cout << "source account owner invalid OR insufficient balance" << endl;
	}
	return totalTransactions;
}

void check_sum(Transaction * t, sql::Connection *con) throw(sql::SQLException){
	PreparedStatement *prep_stmt = NULL;
	if(DEBUG == 1)
	{
		cout << "check_sum" << endl;
	}
	//-- check sufficient sum

	char sql_transaction0[] =
			" SET @senderAccountId := ( select a_id from accounts join transaction_codes \
		on accounts.a_id = transaction_codes.tc_account \
		where a_number = ? \
		 and a_user = ? \
		 and a_balance > ? \
		 and tc_code = ?  AND tc_active = 1 	) ";
	char * sql_transaction = sql_transaction0;
	if (t->type==1){
		char sql_transaction1[] =	" SET @senderAccountId := ( select a_id from accounts  \
				where a_number = ? \
				 and a_user = ? \
				 and a_balance > ? 	) ";
		sql_transaction = sql_transaction1;
	}

	if(DEBUG == 1)
	{
		cout << sql_transaction << endl;
	}
	prep_stmt = con->prepareStatement(sql_transaction);
	prep_stmt->setInt(1, t->src_acc); //a_number
	prep_stmt->setInt(2, user_id);//a_user
	prep_stmt->setInt(3, t->amount); //a_balance
	if(t->type==0){
		prep_stmt->setString(4, t->tan); //tc_code
	}

	// i don't expect an answer
	prep_stmt->execute();
	delete prep_stmt;
	debug_db("@senderAccountId", con);


	//-- check sufficient sum
	char sql_transaction2[] =
			" SET @receiverAccountId := ( select a_id from accounts  \
		       where a_number = ? \
	        )";
	if(DEBUG == 1)
	{
		cout << sql_transaction2 << endl;
	}
	prep_stmt = con->prepareStatement(sql_transaction2);
	prep_stmt->setInt(1, t->dst_acc); //a_number
	// i don't expect an answer
	prep_stmt->execute();
	delete prep_stmt;
	debug_db("@receiverAccountId", con);

	//update sufficient balance flag
	char updateSufficientBalanceFlag[] = "SET @sufficientBalance := IF(@senderAccountId = NULL , 0, 1);" ;
	Statement *stmt = con -> createStatement();
	stmt->execute(updateSufficientBalanceFlag);
	debug_db("@sufficientBalance", con);

	//update valid transactions counter
	char updateValidTransactionsCounter[] = "SET @validTransactionsCounter = @validTransactionsCounter + @sufficientBalance ;" ;
	stmt->execute(updateValidTransactionsCounter);
	debug_db("@validTransactionsCounter", con);
	delete stmt;
}

void insert_transaction_sender(Transaction * t, int confirmed, sql::Connection *con) throw(sql::SQLException){
	//set flag for processed transaction
	char transactionValid[] = "SET @transactionValid = 0;" ;
	Statement *stmt = con -> createStatement();
	stmt->execute(transactionValid);

	PreparedStatement *prep_stmt = NULL;
	if(DEBUG == 1)
	{
		cout << "insert transaction for sender" <<endl;
	}
	char sql_transaction[] = "INSERT INTO transactions (t_account_from, t_amount, t_type, t_code, t_description, t_account_to, t_confirmed) \
			 VALUES ( @senderAccountId, ?, ?, ?, ?, @receiverAccountId, ?)";
	if(DEBUG == 1)
	{
		cout << sql_transaction << endl;
	}
	prep_stmt = con->prepareStatement(sql_transaction);
	//prep_stmt->setInt(1, t->src_acc); //t_account_from
	prep_stmt->setInt(1, 0 - t->amount); // 0 - t_amount
	prep_stmt->setInt(2, 0); //type - sender (0)
	prep_stmt->setString(3, t->tan); //t_code
	prep_stmt->setString(4, t->description); //t_description
	//prep_stmt->setInt(6, t->dst_acc); //t_account_to
	prep_stmt->setInt(5, confirmed); //t_confirmed
	prep_stmt -> execute();
	delete prep_stmt;

	//select last insertion id
	char lastInsertion[] = "SET @transactionValid = (select LAST_INSERT_ID());" ;
	stmt->execute(lastInsertion);
	debug_db("@transactionValid", con);
	//delete insertion if the balance was not enough
	char delete_sender[] = "DELETE FROM transactions WHERE t_id = @transactionValid AND @sufficientBalance = 0; ";
	stmt->execute(delete_sender);

	delete stmt;
}

void insert_transaction_receiver(Transaction * t, int confirmed, sql::Connection *con) throw(sql::SQLException){
	//set flag for processed transaction
	char transactionValid[] = "SET @transactionValid = 0;" ;
	Statement *stmt = con -> createStatement();
	stmt->execute(transactionValid);

	PreparedStatement *prep_stmt = NULL;
	if(DEBUG == 1)
	{
		cout << "insert transaction for receiver" <<endl;
	}
	char sql_transaction[] = "INSERT INTO transactions (t_account_from, t_amount, t_type, t_code, t_description, t_account_to, t_confirmed) \
			 VALUES ( @receiverAccountId, ?, ?, ?, ?, @senderAccountId, ?)";
	if(DEBUG == 1)
	{
		cout << sql_transaction << endl;
	}
	prep_stmt = con->prepareStatement(sql_transaction);
	//prep_stmt->setInt(1, t->dst_acc); //t_account_from
	prep_stmt->setInt(1, t->amount); //t_amount
	prep_stmt->setInt(2, 0); //type - receiver (1)
	prep_stmt->setString(3, t->tan); //t_code
	prep_stmt->setString(4, t->description); //t_description
	//prep_stmt->setInt(6, t->src_acc); //t_account_to
	prep_stmt->setInt(5, confirmed); //t_confirmed
	prep_stmt -> execute();
	delete prep_stmt;

	//select last insertion id
	char lastInsertion[] = "SET @transactionValid = (select LAST_INSERT_ID());" ;
	stmt->execute(lastInsertion);
	debug_db("@transactionValid", con);
	//delete insertion if the balance was not enough
	char delete_receiver[] = "DELETE FROM transactions WHERE t_id = @transactionValid AND @sufficientBalance = 0; ";
	stmt->execute(delete_receiver);
	delete stmt;
}

/*
 * not needed because we display the balance based on the history.
 * */
void update_balance(Transaction * t, sql::Connection *con) throw(sql::SQLException){

	//decrease sum for sender
	PreparedStatement *prep_stmt = NULL;
	if(DEBUG == 1)
	{
		cout << "update_balance" <<endl;
	}
	char sql_transaction[] = "UPDATE accounts \
	           set a_balance = a_balance - ? \
	           where a_number = ? \
	           and a_user = ? \
	           and @sufficientBalance <> 0;"
			;
	if(DEBUG == 1)
	{
		cout << sql_transaction << endl;
	}
	prep_stmt = con->prepareStatement(sql_transaction);
	prep_stmt->setInt(1, t->amount); //t_amount
	prep_stmt->setInt(2, t->src_acc); //t_account_from
	prep_stmt->setInt(3, user_id); //a_user
	prep_stmt -> execute();
	delete prep_stmt;

	//increase sum for receiver
	prep_stmt = NULL;
	if(DEBUG == 1)
	{
		cout << "update_balance" <<endl;
	}
	char sql_transaction2[] = "UPDATE accounts \
			   set a_balance = a_balance + ? \
			   where a_number = ? \
			   and @sufficientBalance <> 0;"
			;
	if(DEBUG == 1)
	{
		cout << sql_transaction << endl;
	}
	prep_stmt = con->prepareStatement(sql_transaction2);
	prep_stmt->setInt(1, t->amount); //t_amount
	prep_stmt->setInt(2, t->dst_acc); //t_account_from
	prep_stmt -> execute();
	delete prep_stmt;
}

void diactivate_transaction_code(Transaction * t, sql::Connection *con) throw(sql::SQLException){

	//disable transaction code
	PreparedStatement *prep_stmt = NULL;
	if(DEBUG == 1)
	{
		cout << "diactivate_transaction_code" <<endl;
	}
	char sql_transaction[] = "UPDATE transaction_codes \
			   SET tc_active = 0 \
			   WHERE tc_code = ? \
			   AND tc_account = @senderAccountId \
			   AND tc_active = 1 \
			   and @sufficientBalance <> 0;"
			;
	if(DEBUG == 1)
	{
		cout << sql_transaction << endl;
	}
	prep_stmt = con->prepareStatement(sql_transaction);
	prep_stmt->setString(1, t->tan); //tc_code
	//prep_stmt->setInt(2, t->src_acc); //tc_account
	prep_stmt -> execute();
	delete prep_stmt;

	//update flag variables
	char transactionValid[] = "SET @codesDisabled = (select ROW_COUNT());" ;
	Statement *stmt = con -> createStatement();
	stmt->execute(transactionValid);
	debug_db("@codesDisabled", con);
	char transactionValid2[] = "SET @validTransactionsCounter := @validTransactionsCounter + @codesDisabled;" ;
	stmt->execute(transactionValid2);
	debug_db("@validTransactionsCounter", con);
	delete stmt;
}

void diactivate_scs_transaction_code(Transaction * t, sql::Connection *con) throw(sql::SQLException){

	//disable transaction code
	PreparedStatement *prep_stmt = NULL;
	if(DEBUG == 1)
	{
		cout << "diactivate_transaction_code" <<endl;
	}
	char sql_transaction[] = "INSERT INTO transaction_codes \
			   (tc_code, tc_account, tc_active)\
			   VALUES(?,?,0);"
			;
	if(DEBUG == 1)
	{
		cout << sql_transaction << endl;
	}
	prep_stmt = con->prepareStatement(sql_transaction);
	prep_stmt->setString(1, t->tan); //tc_code
	prep_stmt->setInt(2, t->src_acc); //tc_account
	prep_stmt -> execute();
	delete prep_stmt;

	//update flag variables
	char transactionValid[] = "SET @codesDisabled = (select ROW_COUNT());" ;
	Statement *stmt = con -> createStatement();
	stmt->execute(transactionValid);
	debug_db("@codesDisabled", con);
	char transactionValid2[] = "SET @validTransactionsCounter := @validTransactionsCounter + @codesDisabled;" ;
	stmt->execute(transactionValid2);
	debug_db("@validTransactionsCounter", con);
	delete stmt;
}

void debug_db(char const name[], sql::Connection *con) throw(sql::SQLException){
	if(DEBUG == 0 )
	{
		return;
	}
	char transactionValid[300] = "INSERT INTO debug (name, value) VALUES (?, (select ";
	strcat(transactionValid, name);
	strcat(transactionValid, "));");
	PreparedStatement *prep_stmt = NULL;
	prep_stmt = con->prepareStatement(transactionValid);
	prep_stmt->setString(1, name); //t_code
	prep_stmt -> execute();
	delete prep_stmt;
}

/**
 * used to escape senstive data
 * http://stackoverflow.com/questions/5665231/most-efficient-way-to-escape-xml-html-in-c-string
 * a more heavy weight library for changing encoding can be used from
 * http://site.icu-project.org/
 * */
void encode(std::string& data) {
    std::string buffer;
    buffer.reserve(data.size());
    for(size_t pos = 0; pos != data.size(); ++pos) {
        switch(data[pos]) {
            case '&':  buffer.append("&amp;");       break;
            case '\"': buffer.append("&quot;");      break;
            case '\'': buffer.append("&apos;");      break;
            case '<':  buffer.append("&lt;");        break;
            case '>':  buffer.append("&gt;");        break;
            default:   buffer.append(&data[pos], 1); break;
        }
    }
    data.swap(buffer);
}

std::vector<std::string> &split(const std::string &s, char delim, std::vector<std::string> &elems) {
    std::stringstream ss(s);
    std::string item;
    while (std::getline(ss, item, delim)) {
        elems.push_back(item);
    }
    return elems;
}


std::vector<std::string> split(const std::string &s, char delim) {
    std::vector<std::string> elems;
    split(s, delim, elems);
    return elems;
}

std::string intToString(int number){
	string String = static_cast<ostringstream*>( &(ostringstream() << number) )->str();
	return String;
}
