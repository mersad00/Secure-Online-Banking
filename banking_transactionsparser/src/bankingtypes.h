
typedef struct  
{
	char tan[16];
	char src_acc[12];
	char dst_acc[12];
	char amount[12];
	char description[251];
} Transaction;


Transaction** load_transactions(char * filename);

Transaction* convert_transaction(char * line);

int process_transaction(Transaction * t, MYSQL * conn);
