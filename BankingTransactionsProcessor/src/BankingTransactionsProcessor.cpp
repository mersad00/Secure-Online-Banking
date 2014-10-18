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

using namespace sql;
using namespace std;

#define DB_SERVER "localhost"
#define DB_USER "root"
#define DB_PASS "SecurePass!"
#define DB_DATABASE "banking"

sql::Connection* initialize_connetion();

int main(int argc, char ** args) {

	if (argc < 2) {
		printf("add file argument\n");
		exit(-1);
	}
	if (argc > 2) {
		printf("too many arguments\n");
		exit(-1);
	}

	sql::Connection *con = initialize_connetion();
	if( con == NULL){
		printf("no connection to db");
		exit(-1);
	}

	//TODO: filter parameters
	char * transactions_file = args[1];
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

	Transaction * t = (Transaction*) malloc(sizeof(*t));
	strncpy(t->tan, tan, 16);
	strncpy(t->src_acc, src_acc, 12);
	strncpy(t->dst_acc, dst_acc, 12);
	strncpy(t->amount, amount, 12);
	strncpy(t->description, description, 251);

	printf("loaded: %s %s %s %s %s\n", t->tan, t->src_acc, t->dst_acc,
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

	return con;
}

int process_transactions(std::vector<Transaction *> transactions, sql::Connection *con){

	cout << "transactions: " << transactions.size() << endl ;

	for (unsigned i=0; i<transactions.size(); i++){
		Transaction * t = transactions.at(i) ;
		if(t==NULL){
			continue;
		}
		cout << "tan " << t->tan << endl;
	}

	return 0;
}

