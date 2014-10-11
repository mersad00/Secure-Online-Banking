#include<stdlib.h>
#include<stdio.h>
#include<string.h>
#include<errno.h>
#include<fcntl.h>
#include<sys/types.h>
#include<sys/stat.h>
#include<unistd.h>

#include "bankingtypes.h"
#include "utils.h"

int main(int argc, char ** args)
{
    if(argc < 2)
    {
        printf("add file argument\n");
        exit(-1);
    }
    if(argc > 2)
    {
        printf("too many arguments\n");
        exit(-1);
    }
    
    //TODO: filter parameters
    char * transactions_file = args[1];
    load_transactions(transactions_file);
        
    return 0;
}

Transaction ** load_transactions(char filename[])
{
	FILE * fp;
	char * line = NULL;
	size_t len = 20;
	ssize_t read;
	
	//TODO: protect against large files!
	
	fp = fopen(filename, "r");
	if( fp == NULL )
	{
		printf("parser failed to open file\n");
		exit(-1);
	}
		
	while((read = getline(&line, &len, fp)) != -1) 
	{
		if(line[0] == 0)
		{
			continue;
		}
		//filter out comments
		if(line[0] == '#')
		{
			continue;
		}
		if(strlen(line) < 5)
		{
			continue;
		}
		convert_transaction(line);
		
	}	
			
	fclose(fp);
	if(line)
		free(line);
		
	return NULL;
}

Transaction * convert_transaction(char * line)
{
	//vulnerabilities!!
	printf("retrieved: %s", line);
	
	char delimiters[] =",";
	char * tan = strtok(line, delimiters);
	char * src_acc = strtok(NULL, delimiters);
	char * dst_acc = strtok(NULL, delimiters);
	char * amount = strtok(NULL, delimiters);
	char * description = strtok(NULL, delimiters);
	
	//vulnerabilities!!
	//printf("tan %s\n", tan);
	//printf("src_acc %s\n", src_acc);
	//printf("dst_acc %s\n", dst_acc);
	//printf("amount %s\n", amount);
	//printf("description %s\n", description);
		
	Transaction * t = (Transaction*) malloc (sizeof (*t));
	strncpy(t->tan, tan, 16);
	strncpy(t->src_acc, src_acc, 12);
	strncpy(t->dst_acc, dst_acc, 12);
	strncpy(t->amount, amount, 12);
	strncpy(t->description, description, 251);
	
	printf("transaction: %s %s %s %s %s\n", t->tan, t->src_acc, t->dst_acc, t->amount, t->description);
	
	return t;
}
