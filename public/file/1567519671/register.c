#include <stdio.h>
#include <string.h>
#include "employee.h"
extern struct Contact PhoneBook[MAX];
extern int size;
void registerEmp() {
	int i;
	char password[] = "qwerty1234";
	char getPW[11];
	printf("Please enter your password of no more than 10 digits.\n");
	for (i = 0; i < 3; i++) {
		printf("Password: \n");
		scanf("%s", getPW);
		int cmp;
		cmp = strcmp(password, getPW);
		if (cmp == 0) {
			printf("New User Name: ");
			scanf("%s", PhoneBook[size].Name);
			printf("PhoneNumber: ");
			scanf("%s", PhoneBook[size].PhoneNumber);
			printf("Registered...\n");
			size++;
		break;
		}
		else if (i == 0) {
			printf(">> Not Matched !!!\n");
		}
		else if (i == 1) {
			printf(">> Not matched(twice)!!!\n");
		}
		else if (i == 2) {
			printf(">> Not matched(3 times)!!!\n");
		}
	}
	if (i == 3) {
		printf(">> You are not allowed to access PhoneBook.\n");
	}
}


