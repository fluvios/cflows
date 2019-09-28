#include <stdio.h>
#include <string.h>
#include "employee.h"
extern struct Contact PhoneBook[MAX];
extern int size;
void printAll() {
	int i;
	printf("<< Display all contacts in the PhoneBook>>\n");
	for (i = 0; i < size; i++) {
		printf("%s\t\t %s\n", PhoneBook[i].Name, PhoneBook[i].PhoneNumber);
	}
}


