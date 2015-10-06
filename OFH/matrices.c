/*  Proyecto fin de carrera*/
/* Optimizacion del stock de farmacos en hospitales*/
/* Fecha: 2014-2015*/
/*Apellidos: Hoyos Martín
Nombre: César*/

#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include "typedef.h"
#include <time.h>
#include "matrices.h"

#define LUNES 0
#define MARTES 1
#define MIERCOLES 2
#define JUEVES 3
#define VIERNES 4
#define SABADO 5
#define DOMINGO 6

#define TAM_BUF 100

void imprimeMatriz(int numFilas, int numColumnas, int **matriz){
	
	int i;	//Variable auxiliar para recorrer filas
	int j;	//Variable auxiliar para recorrer columnas

	for(i=0;i<numFilas;i++){
		printf("%d->\t",i);
		for(j=0;j<numColumnas;j++){
			printf("%d",matriz[i][j]);
		}
	printf("\n");
	}
}

void inicializaMatriz(int numFilas, int numColumnas, int ***matriz){
	int i;
	(*matriz)=(int **) malloc(numFilas*sizeof(int*));
	for(i=0;i<numFilas;i++){
		(*matriz)[i]=(int *) malloc(numColumnas*sizeof(int));
	}
}

void inicializaVector(int dim, int **vector){
	(*vector) = (int*) malloc(dim*sizeof(int));
}