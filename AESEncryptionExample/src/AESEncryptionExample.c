/*
 ============================================================================
 Name        : AESEncryptionExample.c
 Author      : Mohsen
 Version     :
 Copyright   : Your copyright notice
 Description : Hello World in C, Ansi-style
 ============================================================================
 */

#include <stdio.h>
#include <stdlib.h>
#include <string.h>

#include <math.h>
#include <stdint.h>
#include <stdlib.h>
#include <mutils/mcrypt.h>
#include "cencode.h"
#include "cdecode.h"
#include "sha256.h"

/* arbitrary buffer size */
#define SIZE 100

char* encode(const char* input) {
	/* set up a destination buffer large enough to hold the encoded data */
	char* output = (char*) malloc(SIZE);
	/* keep track of our encoded position */
	char* c = output;
	/* store the number of bytes encoded by a single call */
	int cnt = 0;
	/* we need an encoder state */
	base64_encodestate s;

	/*---------- START ENCODING ----------*/
	/* initialise the encoder state */
	base64_init_encodestate(&s);
	/* gather data from the input and send it to the output */
	cnt = base64_encode_block(input, strlen(input), c, &s);
	c += cnt;
	/* since we have encoded the entire input string, we know that
	 there is no more input data; finalise the encoding */
	cnt = base64_encode_blockend(c, &s);
	c += cnt;
	/*---------- STOP ENCODING  ----------*/

	/* we want to print the encoded data, so null-terminate it: */
	*c = 0;

	return output;
}

char* decode(const char* input) {
	/* set up a destination buffer large enough to hold the encoded data */
	char* output = (char*) malloc(SIZE);
	/* keep track of our decoded position */
	char* c = output;
	/* store the number of bytes decoded by a single call */
	int cnt = 0;
	/* we need a decoder state */
	base64_decodestate s;

	/*---------- START DECODING ----------*/
	/* initialise the decoder state */
	base64_init_decodestate(&s);
	/* decode the input data */
	cnt = base64_decode_block(input, strlen(input), c, &s);
	c += cnt;
	/* note: there is no base64_decode_blockend! */
	/*---------- STOP DECODING  ----------*/

	/* we want to print the decoded data, so null-terminate it: */
	*c = 0;

	return output;
}


int encrypt(void* buffer, int buffer_len, /* Because the plaintext could include null bytes*/
char* IV, char* key, int key_len) {
	MCRYPT td = mcrypt_module_open("rijndael-128", NULL, "cbc", NULL);
	int blocksize = mcrypt_enc_get_block_size(td);
	if (buffer_len % blocksize != 0) {
		return 1;
	}

	mcrypt_generic_init(td, key, key_len, IV);
	mcrypt_generic(td, buffer, buffer_len);
	mcrypt_generic_deinit(td);
	mcrypt_module_close(td);

	return 0;
}

int decrypt(void* buffer, int buffer_len, char* IV, char* key, int key_len) {
	MCRYPT td = mcrypt_module_open("rijndael-128", NULL, "cbc", NULL);
	int blocksize = mcrypt_enc_get_block_size(td);
	if (buffer_len % blocksize != 0) {
		return 1;
	}

	mcrypt_generic_init(td, key, key_len, IV);
	mdecrypt_generic(td, buffer, buffer_len);
	mcrypt_generic_deinit(td);
	mcrypt_module_close(td);

	return 0;
}

void display(char* ciphertext, int len) {
	int v;
	for (v = 0; v < len; v++) {
		printf("%d ", ciphertext[v]);
	}
	printf("\n");
}

int main() {

	//customer key get this from user table in DB
	char* mykey = "5UvSoqvtVVtrV2ZW";

	///here is the tan that you received
	char * encryptedTan =
			"FUrlvSwdV18pZI9fl/1ZXFT4dFu3CRkSvmbneNOg8lnFTwrjJlkaEiMjTrxEOn3pBEWQ1tDRYQ1rLvY1HeOLa4EGE/9R4YLtz+ZcJpSDWNUtpZk+/TkC9bH8AsBU7U+x";
	/* decode the base64 */
	char * decoded = decode(encryptedTan);

	//take out iv and hmac and actual encrypted tan
	//TODO: make sure the decoded array is large enough before substr
	char* myiv = calloc(1, 16);
	strncpy(myiv, decoded, 16);

	char* myhmac = calloc(1, 32);
	strncpy(myhmac, decoded + 16, 32);

	int cryptlen = strlen(decoded) - 16 - 32;
	char* mytan = calloc(1, strlen(decoded) - 16 - 32);
	strncpy(mytan, decoded + 16 + 32, strlen(decoded) - 16 - 32);

	//the actual used key is sha256 of the customer key
	int mykeysize = 32;
	char* mykeybyte = calloc(1, 32);
	SHA256_CTX ctx;
	sha256_init(&ctx);
	sha256_update(&ctx, mykey, strlen(mykey));
	sha256_final(&ctx, mykeybyte);

	//let's decrypt the tan!
	char* decipheredTan = calloc(1, cryptlen);
	strncpy(decipheredTan, mytan, cryptlen);
	decrypt(decipheredTan, cryptlen, myiv, mykeybyte, mykeysize);

	//You can now pick up the decipheredTan
	//TODO: trim character 14 at the end of decipheredTan
	//Remember the timestamp is based on UTC
	printf("decrypt: %s\n", decipheredTan);
    //output: decrypt: 2;200;01/23/2015 10:57:08 PM +0100

	free(decoded);
	free(myiv);
	free(myhmac);
	free(mytan);
	free(mykeybyte);
	free(decipheredTan);
	return 0;
}
