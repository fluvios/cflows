#include <stdio.h>
#include "employee.h"

void registerEmp();
void printAll();
void searchByName();
void save();

static int count_service = 0;   // Total number of service requests

int main()
{
	int service;      // a variable for storing user's request
	size = 0;
	do
	{
		printf("============ Telephone Book Management ============");
		printf("\n <<<1. Register\t 2. Print All \t 3. Search  \t 4. Save \t 5. Exit >>>\n");
		printf(" Please enter your service number (1-5)> ");
		scanf("%d", &service);

		switch (service)
		{
		case 1: registerEmp(); break;   // invoke find_ID
		case 2: printAll(); break;
		case 3: searchByName(); break;
		case 4: save(); break;
		}
		count_service++;

	} while (service != 5 && count_service < 50);   // if Exit is not entered, the loop continues
	return 0;
}

