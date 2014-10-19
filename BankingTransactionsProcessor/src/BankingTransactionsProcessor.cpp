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

sql::Connection* initialize_connetion();
PreparedStatement * insert_transaction(Transaction * t, int confirmed, sql::Connection *con);
PreparedStatement * check_sum(Transaction * t, int confirmed, sql::Connection *con);

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
	std::vector<Transaction *> transactions = load_transactions(transactions_file);
	process_transactions(transactions, con);

	return 0;
}

std::vector<Transaction *> load_transactions(char filename[]) {
	FILE * fp;
	char * line = NULL;
	size_t len = 20;
	ssize_t read;
	std::vector<Transaction *> transactions(1);

	//TODO: protect against large files!

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
	t->src_acc = atoi(src_acc);
	t->dst_acc = atoi(dst_acc);
	t->amount = atoi(amount);
	strncpy(t->description, description, 251);

	printf("loaded: %s %d %d %d %s", t->tan, t->src_acc, t->dst_acc,
			t->amount, t->description);

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

int process_transactions(std::vector<Transaction *> transactions, sql::Connection *con){

	cout << "transactions: " << transactions.size() << endl ;
	PreparedStatement *prep_stmt;
	Statement *stmt = con -> createStatement();
	//open DB transaction
	bool transaction_start = stmt -> execute ("START TRANSACTION;");
	cout << "transaction start: " << transaction_start <<endl;
	int processed_transactions = 0;
	for (unsigned i=0; i<transactions.size(); i++){
		Transaction * t = transactions.at(i) ;
		if(t==NULL){
			continue;
		}

		int confirmed = 1;
		int value = t->amount;
		if(value > 10000)
			confirmed = 0;
		cout << "processing: tan " << t->tan
				<< t->src_acc <<" "<<t->dst_acc << " " << t->amount <<" "
				<< user_id << " " << t->description
				<< endl;
		//set tan code as used

		// insert transaction
		//prep_stmt = insert_transaction(t, confirmed, con);
		prep_stmt = check_sum(t, confirmed, con);

		//SET @transactionValid = (select ROW_COUNT());
		stmt -> execute ("SET @transactionValid = (select ROW_COUNT());");

		processed_transactions ++;
	}

	//stmt -> execute ("ROLLBACK;");
	bool transaction_result = false;
	transaction_result = stmt -> execute ("COMMIT;");
	//con->commit();
	//con -> rollback();
	cout << "transaction result: " << transaction_result ;

	return processed_transactions;
}

PreparedStatement * check_sum(Transaction * t, int confirmed, sql::Connection *con){
	PreparedStatement *prep_stmt = NULL;
		cout << "check_sum" <<endl;
		//-- check sufficient sum
		char sql_transaction[] =" SET @sufficientBalance := ( select count(a_balance) from accounts join transaction_codes \
		on accounts.a_number = transaction_codes.tc_account \
		where a_number = ? \
		 and a_user = ? \
		 and a_balance > ? \
		 and tc_code = ? \
		 AND tc_account = ? \
		 AND tc_active = 1 \
		)";

		try {
			cout << sql_transaction << endl;
			prep_stmt = con->prepareStatement(sql_transaction);
			prep_stmt->setInt(1, t->src_acc); //a_number
			prep_stmt->setInt(2, user_id); //a_user
			prep_stmt->setInt(3, t->amount); //a_balance
			prep_stmt->setString(4, t->tan); //tc_code
			prep_stmt->setInt(5, t->src_acc); //tc_account
			prep_stmt -> executeUpdate();

		}
		catch (sql::SQLException &e) {
					cout << "ERROR: SQLException in " << __FILE__;
					cout << " (" << __func__<< ") on line " << __LINE__ << endl;
					cout << "ERROR: " << e.what();
					cout << " (MySQL error code: " << e.getErrorCode();
					cout << ", SQLState: " << e.getSQLState() << ")" << endl;

					if (e.getErrorCode() == 1047) {
						/*
						Error: 1047 SQLSTATE: 08S01 (ER_UNKNOWN_COM_ERROR)
						Message: Unknown command
						*/
						cout << "\nYour server does not seem to support Prepared Statements at all. ";
						cout << "Perhaps MYSQL < 4.1?" << endl;
					}
			}

	return prep_stmt;
}

PreparedStatement * insert_transaction(Transaction * t, int confirmed, sql::Connection *con){
	PreparedStatement *prep_stmt = NULL;
	cout << "insert_transaction" <<endl;
	char sql_transaction[] = "INSERT INTO transactions (t_account_from, t_amount, t_type, t_code, t_description, t_account_to, t_confirmed) \
			 select * from ( select ? as source, ? as amount, ? as type, ? as code, ? as description, ? as destination, ? as confirmed) as tmp"
			 ;

	char sql_transaction1[] = "INSERT INTO transactions (t_account_from, t_amount, t_type, t_code, t_description, t_account_to, t_confirmed) \
				 VALUES ((?), ?, 0, ?, ?, ?, ?) \
			";

	try {
			cout << sql_transaction << endl;
			prep_stmt = con->prepareStatement(sql_transaction);
			prep_stmt->setInt(1, t->src_acc); //t_account_from
			prep_stmt->setInt(2, t->amount); //t_amount
			prep_stmt->setInt(3, 0); //type
			prep_stmt->setString(4, t->tan); //t_code
			prep_stmt->setString(5, t->description); //t_description
			prep_stmt->setInt(6, t->dst_acc); //t_account_to
			prep_stmt->setInt(7, confirmed); //t_confirmed
//			prep_stmt->setInt(7, t->src_acc); //a_number
//			prep_stmt->setInt(8, user_id); //a_user
//			prep_stmt->setInt(9, t->amount); //a_balance
//			prep_stmt->setString(10, t->tan); //tc_code
//			prep_stmt->setInt(11, t->src_acc); //tc_account
//			prep_stmt->setInt(12, 0); //tc_active

			prep_stmt -> executeUpdate();
	}
	catch (sql::SQLException &e) {
			cout << "ERROR: SQLException in " << __FILE__;
			cout << " (" << __func__<< ") on line " << __LINE__ << endl;
			cout << "ERROR: " << e.what();
			cout << " (MySQL error code: " << e.getErrorCode();
			cout << ", SQLState: " << e.getSQLState() << ")" << endl;

			if (e.getErrorCode() == 1047) {
				/*
				Error: 1047 SQLSTATE: 08S01 (ER_UNKNOWN_COM_ERROR)
				Message: Unknown command
				*/
				cout << "\nYour server does not seem to support Prepared Statements at all. ";
				cout << "Perhaps MYSQL < 4.1?" << endl;
			}
	}

	return prep_stmt;
}
