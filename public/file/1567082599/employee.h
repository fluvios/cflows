#define MAX 50

struct Contact
{
	char Name[10];
	char PhoneNumber[13];
};

struct Contact PhoneBook[MAX];

int size;  // store the actual numbers of PhoneBook

extern void registerEmp();
extern void printAll();
extern void searchByName();
extern void save();

