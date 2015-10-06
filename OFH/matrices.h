/*  Proyecto fin de carrera*/
/* Optimizacion del stock de farmacos en hospitales*/
/* Fecha: 2014-2015*/
/*Apellidos: Hoyos Martín
Nombre: César*/

#ifndef MATRICES_H
#define MATRICES_H
#endif

#include "typedef.h"
#include <time.h>

//Función que evalua cada posibilidad para devolver un coste total de medicamentos
void imprimeMatriz(int numFilas, int numColumnas, int **matriz);

//Función para inicializar dinamicamente una matriz
//Debe recibir como entrada para poder definir la matriz
//una dirección de matriz
void inicializaMatriz(int numFilas, int numColumnas, int ***matriz);

//Función para inicializar dinamicamente un vector
void inicializaVector(int dim, int **vector);