/*  Proyecto fin de carrera*/
/* Optimizacion del stock de farmacos en hospitales*/
/* Fecha: 2014-2015*/
/*Apellidos: Hoyos Martín
Nombre: César*/

#include "evalua.h"
#include "ficheros.h"
#include "fechas.h"
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <time.h>
#include "typedef.h"
#include "matrices.h"


#define LUNES 0
#define MARTES 1
#define MIERCOLES 2
#define JUEVES 3
#define VIERNES 4
#define SABADO 5
#define DOMINGO 6

int main(int argc, char *argv[]){
	printf("\n");
	clock_t start = clock();  
	
    
	/*Inicialización de variables para la ejecución del programa*/
	int TAM;	//Dias en el horizonte
	int j;		//Auxiliar para recorrido de matrices
	int i=0;	//Auxiliar para recorrido de matrices
	int k;		//Auxiliar para recalculo de matrices
	int n;		//Auxiliar para recalculo y traspaso de matrices
	int limite=1;	//Limite para el calculo del tope de posibilidades
	int error=0;	//Variable de error
	MEDICINE medicine;	//Estructura para mantener la información del medicamento


	int numPedidos;
	int numDiasNo=argc-3;
	int diasMes;
	int aux=0;
	
	/*Comprobamos que el numero de argumentos recibidos es el correto*/
	if (argc<3){	
		error=1;
		printf("ERROR1:\nNumero de argumentos de la funcion incorrectos\n");
		printf("La llamada a la funcion debe ser \"%s\" \"numero de dias en el horizonte\" \"numero de pedidos en el horizonte\" \"OPCIONAL:fecha de dias de no pedido\":\n",argv[0]);
		printf("dd/mm/aaaa\n");
	}else{
		for(i=0;argv[1][i]!='\0';i++){
			if(argv[1][i]<'0'||argv[1][i]>'9'){
				error=2;
			}
		}
		/*Comprobamos que el numero de días en el horizonte tiene un formato correcto*/
		if(error!=0){
			printf("ERROR2:\nValor de numero de dias en el horizonte incorrecto. Introduzca valor numerico\n");
		}else{
			
			TAM=atoi(argv[1]);
			for(i=0;argv[2][i]!='\0';i++){
				if(argv[2][i]<'0'||argv[2][i]>'9'){
					error=3;
				}
			}
			/*Comprobamos que el numero de dias de pedido tiene un formato correcto*/
			if(error!=0){
				printf("ERROR3:\nValor de numero de pedidos en el horizonte incorrecto. Introduzca valor numerico\n");
			}else{
						
				numPedidos=atoi(argv[2]);
				/*Comprobamos que el numero de días de pedido no sea mayor que el numero de dias posible*/
				if(numPedidos>TAM){
					error=4;
					printf("ERROR4:\nNumero de dias de pedido mayor que dias en el horizonte\n");
				}else{
				//Inicializar vector diasNO
						int diasNO[TAM];
						for(i=0;i<TAM;i++){
							diasNO[i]=0;
						}
					
					/*Comprobamos que se han introducido fechas sin error:*/
					if(argc>3){
						
						int *FechaActual;
						inicializaVector(3, &FechaActual);

						fechaHoy(FechaActual);
						//Fecha introducida por linea de comandos
						k=0;
/*----------------------Inicializamos la matriz de fechas para trabajar---------
------------------------más comodamente con ellas al ser enteros--------------*/
						char auxFecha[5];
						int ** Fecha;
						inicializaMatriz(numDiasNo, 3, &Fecha);
						//Realizamos un bucle para recorrer todas las fechas introducidas
						//por la línea de comandos
						for(n=0;n<numDiasNo;n++){	
							j=0;						
							k=0;	
							//Y otro para ir separando día, mes y año de la fecha
							//para facilitar el trabajo con las mismas
							for(i=0;argv[n+3][i]!='\0';i++){
								if(argv[n+3][i]=='/'){
									auxFecha[k]='\0';
									Fecha[n][j]=atoi(auxFecha);
									j++;
									k=0;
								}else if(argv[n+3][i]<'0'||argv[n+3][i]>'9'){
									error=5;
								}else{
									auxFecha[k]=argv[n+3][i];
									k++;
								}
							}
							auxFecha[k]='\0';
							Fecha[n][j]=atoi(auxFecha);
						}
/*----------------------Comprobamos si las fechas están en el horizonte pedido---*/							
						for(n=0;n<numDiasNo;n++){
							if(Fecha[n][1]>12||Fecha[n][1]<1){
								error=6;
							}else if(Fecha[n][0]<1){
								error=6;
							}switch (Fecha[n][1]){
								case 1:
									diasMes = 31;
									if(Fecha[n][0]>31){
										error=6;
									}
									break;
								case 2:
									if(bisiesto(Fecha[n][2])==1){
										diasMes=29;
										if(Fecha[n][0]>29){
											error=6;
										}
									}
									else{
										diasMes=28;
										if(Fecha[n][0]>28){
											error=6;
										}
									}
									break;
								case 3:
									diasMes=31;
									if(Fecha[n][0]>31){
										error=6;
									}
									break;
								case 4:
									diasMes=30;
									if(Fecha[n][0]>30){
										error=6;
									}
									break;
								case 5:
									diasMes=31;
									if(Fecha[n][0]>31){
										error=6;
									}
									break;
								case 6:
									diasMes=30;
									if(Fecha[n][0]>30){
										error=6;
									}
									break;
								case 7:
									diasMes=31;
									if(Fecha[n][0]>31){
										error=6;
									}
									break;
								case 8:
									diasMes=31;
									if(Fecha[n][0]>31){
										error=6;
									}
									break;
								case 9:
									diasMes=30;
									if(Fecha[n][0]>30){
										error=6;
									}
									break;
								case 10:
									diasMes=31;
									if(Fecha[n][0]>31){
										error=6;
									}
									break;
								case 11:
									diasMes=30;
									if(Fecha[n][0]>30){
										error=6;
									}
									break;
								case 12:
									diasMes=31;
									if(Fecha[n][0]>31){
										error=6;
									}
									break;
								default:
									error=6;
							}
							//Si el año es menor que el actual
							if(FechaActual[2]>Fecha[n][2]){ 
								error=6;					//error
							}else{
								aux=Fecha[n][2]-FechaActual[2];	
								//si la diferencia de años es mayor que uno
								if(aux>1){	
									//error: estará fuera del horizonte
									error=6;
								}else{
									//si el mes es pasado al actual
									if(FechaActual[1]>Fecha[n][1]){ 
										error=6;			//error
									}else{		//otro caso
										//Distinto año
										if (aux==1){ 
										//	printf("Distinto año\n");
											if(!((Fecha[n][1]==1)&&(FechaActual[1]==12))){ //no es enero y diciembre
												error=6;		//error
											}else{ //Si lo son
												if(aux==1){ //Si son de meses diferentes
													if(FechaActual[0]+TAM-diasMes<Fecha[n][0]){//Fuera del horizonte
														error=6; //error
													}else{
														aux=diasMes-FechaActual[0];
														aux=aux+Fecha[n][0];
														diasNO[aux]=1;
													}
												}
											}
												
										}else{
											aux=Fecha[n][1]-FechaActual[1];
											if(aux>1){
												error=6;
											}else{ 
												if(aux==1){ //Si son de meses diferentes
													if(FechaActual[0]+TAM-diasMes-1<Fecha[n][0]){//Fuera del horizonte
														error=6; //error
													}else{
															aux=diasMes-FechaActual[0];
															aux=aux+Fecha[n][0];
															diasNO[aux]=1;
													}
													
												}else{//CASO del mismo mes
													if(FechaActual[0]>Fecha[n][0]){//Fecha antigua
														error=6;
													}else if(FechaActual[0]+TAM-1<Fecha[n][0]){//Fecha fuera del horizonte
														error=6;
													}else{
														aux=Fecha[n][0]-FechaActual[0];
														diasNO[aux]=1;
													}
												}
											}
										}
									}
								}
							}
						}
					}
					/*Comprobamos si es error sintactico*/
					if (error==5){
						printf("Error=%d\n",error);
						printf("ERROR5:\nDias incorrectos. Utilizar la siguiente notacion:\n");
						printf("dd/mm/yyyy\n");
					/*Comprobamos si la fecha está dentro de fechas posibles*/
					}else if(error==6){
						printf("ERROR6:\nFecha incorrecta fuera del horizonte\n");
					}else{

/*--------------------------------------------------------------------------
------------------------Calculo de primera matriz---------------------------
--------------------------------------------------------------------------*/
						for(j=0;j<TAM;j++){
							limite=limite*2;
						}
						
						int cambio=1;


						//Reserva de memoria para matrix1
						int **matrix1;
						
						inicializaMatriz(limite, TAM, &matrix1);

						//Inicializamos el primer vector a 0
						for (j=0;j<TAM;j++){
							matrix1[0][j]=0;
						}

						//Bucle de llene de la matriz

						for (i=1;i<limite;i++){
							if(matrix1[i-1][TAM-1]==0){ //Alternancia del primer bit
								matrix1[i][TAM-1]=1;
							}else{
								matrix1[i][TAM-1]=0;
							}
							for(j=TAM-2;j>=0;j--){
								cambio=1;
								for(k=TAM-1;k>j;k--){
									if(matrix1[i-1][k]==0){
										cambio=0;
									}
								}

								if(matrix1[i-1][j]==0&&cambio==1){
									matrix1[i][j]=1;
								}else if(matrix1[i-1][j]==1&&cambio==1){
									matrix1[i][j]=0;
								}else{
									matrix1[i][j]=matrix1[i-1][j];
								}
							}
						}
/*---------------------------------------------------------------------------
------------------------Matriz de pedidos quitando dias NO posibles----------
---------------------------------------------------------------------------*/
						int **matrix;
						inicializaMatriz(limite, TAM, &matrix);
						
						for(i=0;i<limite;i++){
							for(j=0;j<TAM;j++){
								matrix[i][j]=matrix1[i][j];
								if(diasNO[j]==1){
									matrix[i][j]=0;
								}
							}
						}

						int guardar;
						k = 0;
					
						for(i=0;i<limite;i++){
							guardar=1;
							for(j=1;j<TAM;j++){
								if(matrix[i][j-1]==1&&matrix[i][j]==1){
									guardar=0;
								}
							}
							if(guardar==1){
								for(j=0;j<TAM;j++){
									matrix1[k][j]=matrix[i][j];
								}
								k++;
							}
						}
						int filasPedidos=k;
					
						int h=0;
						int auxIguales=0;

						for(i=0;i<filasPedidos;i++){
							guardar=1;
							for(k=0;k<i;k++){
								auxIguales=0;
								for(j=0;j<TAM;j++){
									if(matrix1[k][j]==matrix1[i][j]){
										auxIguales++;
									}
								}
								if(auxIguales==TAM){
									guardar=0;
								}
							}
							if(guardar==1){
								for(j=0;j<TAM;j++){
									matrix[h][j]=matrix1[i][j];
								}
								h++;
							}
						}
						filasPedidos=h;

/*---------------------------------------------------------------------------------------
------------------------Matriz sin semanas repetidas con numero de pedidos solicitados---
---------------------------------------------------------------------------------------*/
					
						k=0;
						for(i=0;i<filasPedidos;i++){
							guardar=1;
							int auxNumPedidos=0;
							for(j=0;j<TAM;j++){
								if(matrix[i][j]==1){
									auxNumPedidos++;
								}
							}
							if(auxNumPedidos!=numPedidos){
								guardar=0;
							}
							if(guardar==1){
							//	printf("%d->\t",k);
								for(j=0;j<TAM;j++){
									matrix1[k][j]=matrix[i][j];
								}
								k++;
							}
						}
						filasPedidos=k;
					//	imprimeMatriz(filasPedidos, TAM, matrix1);
						
/*----------------------------------------------------------------------------------------
------------------------Una vez obtenidas las posibilidades de salida---------------------
------------------------procedemos a la lectura del fichero          ---------------------
----------------------------------------------------------------------------------------*/

						/*
								Se realizan las operaciones pertinentes
								de apertura, lectura y cerrado de fichero
								con el que intercambiar información con
								el programa en php para la web.
								Se almacenan los datos en la estructura 
								del medicamento.
						*/
						if(ficheros(TAM, &medicine)==1){
							printf("ERROR7: Lectura de fichero no realizada\n");
							error = 7;
						}else{
							
/*----------------------------------------------------------------------------------------
------------------------Procedemos al calculo de la matriz con las posibilidades----------
------------------------de pedido                                               ----------
----------------------------------------------------------------------------------------*/
							//Inicializamos variables auxiliares utiles para la obtención 
							//de las matrices pertinentes
							int g=0;
							h=0;
							n=0;
							
							//Matriz base de combinaciones
							int exp4=1;
							//Obtenemos primero el numero de combinaciones posibles								
							for(i=0;i<numPedidos;i++){
								exp4=exp4*medicine.nTamPedidos;
							}
							
							int divisor = exp4/medicine.nTamPedidos; //Variable auxiliar para acceder al vector de la forma adecuada
							//Matriz de combinaciones
							int **matrixAux1;
							
							inicializaMatriz(exp4, numPedidos, &matrixAux1);
							
							for(j=0;j<numPedidos;j++){	//Luego por filas
								for(i=0;i<exp4;i++){	//Primero por columnas
									matrixAux1[i][j]=medicine.vTamPedidos[(i/divisor)%medicine.nTamPedidos];
								}
								divisor=divisor/medicine.nTamPedidos;	//Disminuimos la auxiliar para acceder a la posicion correcta
							}

							//Imprimimos la matriz por pantalla
							//imprimeMatriz(exp4, numPedidos, matrixAux1);

						//	printf("Numero de posibilidades total: %d\n",filasPedidos*exp4);
							
							//Matriz definitiva
							free(matrix);
							matrix=NULL;
							
							inicializaMatriz(filasPedidos*exp4, TAM, &matrix);
							
							// Bucles para la obtencion de la matriz definitiva
							for(i=0;i<filasPedidos;i++){	// Por cada fila de la matriz de dias de pedidos y no
								for(k=0;k<exp4;k++){		// Accedemos todas las veces de las combinaciones posibles
									for(j=0;j<TAM;j++){		// En el recorrido
										if(matrix1[i][j]==1){	// Si es 1 se cambia por el valor correspondiente
											matrix[n][j]=matrix1[i][j]*matrixAux1[k][g];
											g++;
											
										}else{	//Si es 0 se deja igual
											matrix[n][j]=matrix1[i][j];
										}							
									}
									g=0;	//Al finalizar cada pasada reiniciamos el contador g a 0
									n++;	//Y pasamos a rellenar la siguiente fila
								}
							}
							
							filasPedidos=n;		
							// Imprimimos por pantalla todas las posibilidades				
							
						/*	printf("Matriz posibilidades:\n");
							imprimeMatriz(filasPedidos, TAM, matrix); */
														
							if(filasPedidos==0){
								printf("ERROR8: No se puede obtener ninguna posibilidad con los datos introducidos\n");
								error = 8;
							}else{
								// Una vez obtenidas todas las posibles combinaciones
								// para un determinado horizonte, procedemos al cálculo
								// y consiguiente obtención de los días de pedidos
								// útiles para el farmaceútico
								/* evalua(int* pedidos, int horizonte, int retraso, int* stock) */
								int x;
								float J;
								float Jmin = 1000;
								int *stock;
								inicializaVector(TAM, &stock);
								int *stockOptimo;
								inicializaVector(TAM, &stockOptimo);
								int *vectorOptimo;
								inicializaVector(TAM, &vectorOptimo);

								for(x=0; x<filasPedidos; x++){
									inicializa(stock, TAM);
									J = evalua(matrix[x], TAM, 0, stock, &medicine);
								//	printf("\n%d->\tJ = %f\n",x,J);
									if(J <Jmin){
										Jmin = J;
										for(k=0; k<TAM; k++){
											vectorOptimo[k]=matrix[x][k];
											stockOptimo[k]=stock[k];
										}
									}
								}
								if(Jmin==1000){
									printf("ERROR9: No existe ninguna posibilidad válida para nuestro problema\n");
									error = 9;
								}else{
									printf("Jmin= %f\nPedido:", Jmin);
									for(x=0;x<TAM; x++){
										printf("%d ",vectorOptimo[x] );
									}
									printf("\nOptimo:");
									for(x=0;x<TAM; x++){
										printf("%d ",stockOptimo[x] );
									}
									printf("\n");

									//char **FechasOptimas;
									int ** FechasPedido;
									inicializaMatriz(numPedidos, 3, &FechasPedido);
									//A partir de obtener los valores optimos de días de pedidos
									//debemos obtener ahora las fechas con su correspondiente valor
									
									obtieneFechasPedidos(vectorOptimo, TAM, FechasPedido);
								}
							}
						}
					}
				}
			}
		}
	}	
	printf("\n");
	printf("Tiempo transcurrido: %f\n\n", ((double)clock() - start) / CLOCKS_PER_SEC);	
	
	return error;
}