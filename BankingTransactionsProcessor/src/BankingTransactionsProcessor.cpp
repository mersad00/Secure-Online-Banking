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

#include "bankingtypes.h"

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

#define DB_SERVER "localhost"
#define DB_USER "root"
#define DB_PASS "SecurePass!"
#define DB_DATABASE "banking"

#define DEBUG 0

sql::Connection* initialize_connetion();
void check_sum(Transaction * t, sql::Connection *con) throw(sql::SQLException);
void insert_transaction_sender(Transaction * t, int confirmed, sql::Connection *con) throw(sql::SQLException);
void insert_transaction_receiver(Transaction * t, int confirmed, sql::Connection *con) throw(sql::SQLException);
void update_balance(Transaction * t, sql::Connection *con) throw(sql::SQLException);
void diactivate_transaction_code(Transaction * t, sql::Connection *con) throw(sql::SQLException);
void debug_db(char const name[], sql::Connection *con) throw(sql::SQLException);

int user_id = 0;

int main(int argc, char ** args) {

	if (argc < 3) {
		printf("usage: owner_id transactions_file\n");
		exit(-1);
	}
	if (argc > 3) {
		printf("too many arguments\n");
		exit(-1);
	}

	sql::Connection *con = initialize_connetion();
	if( con == NULL){
		printf("no connection to db");
		exit(-1);
	}

	//TODO: filter parameters
	user_id = atoi(args[1]);
	char * transactions_file = args[2];
	std::vector<Transaction *> transactions = load_transactions(
			transactions_file);
	try {
		process_transactions(transactions, con);
	} catch (sql::SQLException &e) {
		Statement *stmt = con -> createStatement();
		stmt -> execute ("ROLLBACK;");
		delete stmt;
		cout << "# ERR: SQLException " << endl;
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

std::vector<Transaction *> load_transactions(char filename[]) {
	FILE * fp;
	char * line = NULL;
	size_t len = 20;
	ssize_t read;
	std::vector<Transaction *> transactions(1);

	fp = fopen(filename, "r");
	if (fp == NULL) {
		printf("parser failed to open file\n");
		exit(-1);
	}

	//	conn = init_bank_connection();
	while ((read = getline(&line, &len, fp)) != -1) {
		if (line[0] == 0) {
			continue;
		}
		//filter out comments
		if (line[0] == '#') {
			continue;
		}
		if (strlen(line) < 5) {
			continue;
		}
		Transaction * t = convert_transaction(line);
		if(t==NULL)
			continue;
		transactions.push_back(t);
	}

	fclose(fp);
	if (line)
		free(line);

	return transactions;
}

Transaction * convert_transaction(char * line) {
	//TODO: vulnerabilities!!
	//printf("retrieved: %s", line);

	char delimiters[] = ",";
	char * tan = strtok(line, delimiters);
	char * src_acc = strtok(NULL, delimiters);
	char * dst_acc = strtok(NULL, delimiters);
	char * amount = strtok(NULL, delimiters);
	char * description = strtok(NULL, delimiters);

	//TODO: vulnerabilities!!
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
	strncpy(t->tan, tan, 16);
	t->src_acc = strtol (src_acc,NULL,10);
	t->dst_acc = strtol(dst_acc,NULL,10);
	t->amount = strtol(amount,NULL,10);
	if(t->amount < 0){
		cout<<"amount is not allowed to be negative"<<endl;
		exit(-1);
	}
	strncpy(t->description, description, 251);
	if(DEBUG == 1)
	{
		printf("loaded: %s %d %d %d %s", t->tan, t->src_acc, t->dst_acc,
			t->amount, t->description);
	}
	return t;
}

sql::Connection * initialize_connetion() {

	sql::Connection *con;
	sql::Driver *driver;

	driver = get_driver_instance();
	con = driver->connect(DB_SERVER, DB_USER, DB_PASS);
	/* Connect to the MySQL test database */
	con->setSchema(DB_DATABASE);

	/* disable the autocommit */
	//con -> setAutoCommit(0);

	return con;
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
		if(DEBUG == 1)
		{
			cout << "processing: tan " << t->tan
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
		diactivate_transaction_code(t, con);

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
	char sql_transaction[] =
			" SET @sufficientBalance := ( select count(a_balance) from accounts join transaction_codes \
		on accounts.a_number = transaction_codes.tc_account \
		where a_number = ? \
		 and a_user = ? \
		 and a_balance > ? \
		 and tc_code = ? \
		 AND tc_account = ? \
		 AND tc_active = 1 \
		)";
	if(DEBUG == 1)
	{
		cout << sql_transaction << endl;
	}
	prep_stmt = con->prepareStatement(sql_transaction);
	prep_stmt->setInt(1, t->src_acc); //a_number
	prep_stmt->setInt(2, user_id); //a_user
	prep_stmt->setInt(3, t->amount); //a_balance
	prep_stmt->setString(4, t->tan); //tc_code
	prep_stmt->setInt(5, t->src_acc); //tc_account
	// i don't expect an answer
	prep_stmt->execute();
	delete prep_stmt;
	debug_db("@sufficientBalance", con);
	//update valid transactions counter
	char updateValidTransactionsCounter[] = "SET @validTransactionsCounter = @validTransactionsCounter + @sufficientBalance ;" ;
	Statement *stmt = con -> createStatement();
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
			 VALUES ( ?, ?, ?, ?, ?, ?, ?)";
	if(DEBUG == 1)
	{
		cout << sql_transaction << endl;
	}
	prep_stmt = con->prepareStatement(sql_transaction);
	prep_stmt->setInt(1, t->src_acc); //t_account_from
	prep_stmt->setInt(2, 0 - t->amount); //t_amount
	prep_stmt->setInt(3, 0); //type
	prep_stmt->setString(4, t->tan); //t_code
	prep_stmt->setString(5, t->description); //t_description
	prep_stmt->setInt(6, t->dst_acc); //t_account_to
	prep_stmt->setInt(7, confirmed); //t_confirmed
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
			 VALUES ( ?, ?, ?, ?, ?, ?, ?)";
	if(DEBUG == 1)
	{
		cout << sql_transaction << endl;
	}
	prep_stmt = con->prepareStatement(sql_transaction);
	prep_stmt->setInt(1, t->dst_acc); //t_account_from
	prep_stmt->setInt(2, t->amount); //t_amount
	prep_stmt->setInt(3, 0); //type
	prep_stmt->setString(4, t->tan); //t_code
	prep_stmt->setString(5, t->description); //t_description
	prep_stmt->setInt(6, t->src_acc); //t_account_to
	prep_stmt->setInt(7, confirmed); //t_confirmed
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
			   AND tc_account = ? \
			   AND tc_active = 1 \
			   and @sufficientBalance <> 0;"
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
