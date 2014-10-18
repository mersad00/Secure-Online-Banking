/*
 * banking_transactionprocessor.c
 *
 *  Created on: Oct 12, 2014
 *      Author: cipri
 */

#define DB_SERVER "localhost"
#define DB_USER "root"
#define DB_PASS "SecurePass!"
#define DB_DATABASE "banking"

extern MYSQL * conn ;

MYSQL * init_bank_connection()
{
	MYSQL * conn = mysql_init(NULL);
	/* Connect to database */
	if(!mysql_real_connect(conn, DB_SERVER, DB_USER, DB_PASS, DB_DATABASE, 0, NULL, 0))
	{
		fprintf(stderr, "%s\n", mysql_error(conn));
		exit(1);
	}

//	/* send SQL query */
//	if(mysql_query(conn, "show tables")){
//		fprintf(stderr, "%s\n", mysql_error(conn));
//		exit(1);
//	}
//
//	MYSQL_RES * res = mysql_use_result(conn);
//	MYSQL_ROW row ;
//
//	/* output tables name */
//	printf("mySql tables: \n");
//	while((row = mysql_fetch_row(res))!=NULL)
//	{
//		printf("%s\n", row[0]);
//	}
//
//	/* close connection */
//	mysql_free_result(res);

	printf(">>CONNECTED to Database successfully!\n");

	return conn;
}

void close_database_connection(MYSQL * conn)
{
	mysql_close(conn);
}

int process_transaction(Transaction *t)
{
	//printf("mysql client version %s\n", mysql_get_client_info());


	char * tan = t->tan;
	validate_tan(t->tan, t->src_acc);

	return -1;
}

int validate_tan(char * tan, char * acc_number)
{
	MYSQL_STMT *stmt;
	char *sql;

	 // Bind variables
	 MYSQL_BIND param[1], result[1];

	 sql = "SELECT tc_account FROM transaction_codes WHERE tc_code = ?";
	 char tc_code[16];
	 unsigned long	  tc_code_length;
	 int tc_account;
	 int tc_active;
	 my_bool is_null[3];

	 // Allocate statement handler
	 stmt = mysql_stmt_init(conn);

	 if (stmt == NULL) {
		 printf("Could not initialize statement handler\n");
		 return -1;
	 }

	 // Prepare the statement
	 if (mysql_stmt_prepare(stmt, sql, strlen(sql)) != 0) {
		 printf("Could not prepare statement\n");
		 return -1;
	 }

	 // Initialize the result column structures
	 memset (param, 0, sizeof (param)); /* zero the structures */
	 memset (result, 0, sizeof (result)); /* zero the structures */

	 // Init param structure
	 printf("Verfiy tan %s %d\n", tan, strlen(tan));
	 // Select
	 /* set up CHAR parameter */
	 param[0].buffer_type = MYSQL_TYPE_STRING;
	 param[0].buffer = (void *) tan;
	 param[0].buffer_length = sizeof (tan);
	 //param[0].is_null = 0;
	 /* is_unsigned need not be set, length is set later */


	 // Result
	 /* set up CHAR parameter */
//	 result[0].buffer_type = MYSQL_TYPE_VARCHAR;
//	 result[0].buffer = (void *) tc_code;
//	 result[0].buffer_length = sizeof (tc_code);
//	 result[0].length = &tc_code_length;
//	 result[0].is_null = &is_null[0];
	 /* is_unsigned need not be set */

	 /* set up INT parameter */
	 result[0].buffer_type = MYSQL_TYPE_INT24;
	 result[0].buffer = (void *) &tc_account;
	 result[0].is_unsigned = 0;
	 result[0].is_null = &is_null[0];
	 /* buffer_length, length need not be set */

	 /* set up INT parameter */
//	 result[2].buffer_type = MYSQL_TYPE_BIT;
//	 result[2].buffer = (void *) &tc_active;
//	 result[2].is_unsigned = 0;
//	 result[2].is_null = &is_null[2];
	 /* buffer_length, length need not be set */

	 // Bind param structure to statement
	 if (mysql_stmt_bind_param(stmt, param) != 0) {
		 printf("Could not bind parameters\n");
		 return -1;
	 }

	 // Bind result
	 if (mysql_stmt_bind_result(stmt, result) != 0) {
		 printf("Could not bind results\n");
		 return -1;
	 }

	 // Execute!!
	 if (mysql_stmt_execute(stmt) != 0) {
		 printf("Could not execute statement\n");
		 return -1;
	 }

	 if (mysql_stmt_store_result(stmt) != 0) {
		 printf("Could not buffer result set\n");
		 return -1;
	 }


	 // Fetch
	 if(mysql_stmt_fetch (stmt) == 0){
		 printf("selected tan %s\n", tc_code);
		 printf("selected account %d\n", tc_account);
		 printf("selected active %d\n", tc_active);
	 }
	 else{
		 printf("no results\n");
	 }

	 // Deallocate result set
	 mysql_stmt_free_result(stmt); /* deallocate result set */

	 // Close the statement
	 mysql_stmt_close(stmt);


	return 0 ;
}
