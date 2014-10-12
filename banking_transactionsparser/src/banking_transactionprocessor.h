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

int process_transaction(Transaction *t, MYSQL * conn)
{
	//printf("mysql client version %s\n", mysql_get_client_info());



	return -1;
}
