CC = gcc
CFLAGS = -Wall -g -c

OBJS = empMain.o register.o print.o search.o save.o
all : main
empMain.o: empMain.c
	$in(CC) $(CFLAGS) $<
%.o: %.c
	$(CC) $(INCLUDE) $(CFLAGS) $<
main: $(OBJS)
	$(CC) -o main $(OBJS)
clean:
	rm -f main $(OBJS)

