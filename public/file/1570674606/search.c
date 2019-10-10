#include <stdio.h>
#include <string.h>
#include "employee.h"
extern struct Contact PhoneBook[MAX];
extern int size;
void searchByName() {
	int i;
	int cmp;
	char searchName[10];
	printf("Enter a name to search: ");
	scanf("%s", searchName);
	for (i = 0; i < size; i++) {
		cmp = strcmp(PhoneBook[i].Name, searchName);
		if (cmp == 0) {
			printf("%s\t\t%s\n", PhoneBook[i].Name, PhoneBook[i].PhoneNumber);
			break;
		}
	}
	if (i == size)
		printf("Oops! %s is not in the PhoneBook.\n", searchName);
}

