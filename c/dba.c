#include <stdlib.h>
#include <string.h>
#include <stdio.h>
#include <unistd.h>
#include <mysql.h>


void show_error(MYSQL *mysql)
{
  printf("Error(%d) [%s] \"%s\"", mysql_errno(mysql),
                                  mysql_sqlstate(mysql),
                                  mysql_error(mysql));
  mysql_close(mysql);
  exit(-1);
}

void sumafinal(int *posiciones, int linea, char *archivo, int tamano, char *fuente, char *color, float cantidades, float preciobase, float dto, float iva, float preciofinal){
  int miposicion;
  float mivalor;
  char miorden[200];
  for(int w=0;w<5;w++){
    miposicion=posiciones[w]+30;
    mivalor=0;
    switch(w){
      case 0:
	mivalor=cantidades;
	if(mivalor<100){
	  miposicion+=6;
	  if(mivalor<10){
	    miposicion+=6;
	  }
	}
	break;
      case 1:
	mivalor=preciobase;
	if(mivalor<10000){
	  miposicion+=5;
	  if(mivalor<1000){
	    miposicion+=5;
	    if(mivalor<100){
	      miposicion+=5;
	      if(mivalor<10){
		miposicion+=5;
	      }
	    }
	  }
	}
	break;
      case 2:
	mivalor=dto;
	if(mivalor<1000){
	  miposicion+=5;
	  if(mivalor<100){
	    miposicion+=5;
	    if(mivalor<10){
	      miposicion+=5;
	    }
	  }
	}
	break;
      case 3:
	mivalor=iva;
	if(mivalor<1000){
	  miposicion+=5;
	  if(mivalor<100){
	    miposicion+=5;
	    if(mivalor<10){
	      miposicion+=5;
	    }
	  }
	}
	break;
      case 4:
	mivalor=preciobase;
	miposicion+=30;
	if(mivalor<100000){
	  miposicion+=5;
	  if(mivalor<10000){
	    miposicion+=5;
	    if(mivalor<1000){
	      miposicion+=5;
	      if(mivalor<100){
		miposicion+=5;
		if(mivalor<10){
		  miposicion+=5;
		}
	      }
	    }
	  }
	}
	break;
    }
    sprintf(miorden,"convert -font %s -fill %s -pointsize %d -draw \"text %d,%d '%.2f'\" %s %s",fuente,color,tamano,miposicion,linea+30,mivalor,archivo,archivo);
    system(miorden);
  }
}


float redondeados(float a)
{
  char numero[25];
  float r1,r2;
  sprintf(numero,"%.2f",a);
  r1=atof(numero);
  if(a>=0){
    r2=atof(numero)+0.01;
  } else {
    r2=atof(numero)-0.01;
  }
  if((r2-a)>=(a-r1)){
    return r1;
  } else {
    return r2;
  }
}


int redondearint(float a)
{
  char numero[25];
  int r1,r2;
  sprintf(numero,"%f",a);
  r1=atoi(numero);
  if(a>0){
    r2=atoi(numero)+1;
  } else {
    r2=atoi(numero)-1;
  }
  if((r2-a)>=(a-r1)){
    return r1;
  } else {
    return r2;
  }
}

int maximoentero(int a,int b)
{
  if(a>b){
    return a;
  } else {
    return b;
  }
}

int findecadena(char *cadena, int maximo)
{
  int posicion=0;
  for(int i=0;i<maximo;i++){
    if(cadena[i]=='\0') return i;
  }
  return maximo+1;
}

int ultimoespacio(char *cadena, int maximo)
{
  int posicion=0;
  for(int i=0;i<maximo;i++){
    if(cadena[i]==' ') posicion=i;
  }
  return posicion;
}

int main(int argc, char *argv[])
{
  MYSQL *mysql;
  const char *query;
  MYSQL_RES *result;
  MYSQL_ROW row;
  char clienteasunto[100],clientecuerpo[2048],outfile[18],outputfile[35],inputfile[12], inputfileb[13], clientenotas[2048], ventadescripcion[2048], idfra[10], consulta[500], orden[500],inputfilec[13], sendmails[2], workingfile[10], workingname[5], workingext[5], usuario[20], database[20], contras[20], empresanombre[50], empresacif[20], empresadireccion[100], empresacp[12], empresalocalidad[20], empresaprovincia[20], empresatelefono[10], empresaweb[50], empresamail[50], empresalogo[10], fondo[20], fondopar[20], fondoimpar[20], fondotitulos[20], fondotitulossuma[20], fondonotas[20], fondosumas[20], fondohueco[20], fuente[20], color[20], colorsumas[20], colortitulossumas[20], colorcabecera[20], colortitulos[20], colorproductospar[20], colorproductosimpar[20], colorcorrespondencia[20], outdir[20], clientedni[11], clientenombre[61], clienteapellidos[121], clientedireccion[201], clientecp[6], clientepoblacion[101], clienteprovincia[41], clientenfra[10], clientemail[101], clientefecha[11], ventaproducto[101], titulos[7][20]={"Cantidad","Producto","Descripción","Precio Ud.","Dto.","IVA","Precio"}, titulossuma[5][20]={"Cantidad","Base Imponible","Descuentos","IVA","PrecioTotal"}, listaproducto[7][25], listadescripcion[65][40], listanotas[30][110], *comando;
  int eiud,lud,unilinea,medlinea,vuelta, i, a, x, y, z, longitudproducto, longituddescripcion, contador, totalrows, numfields, lineacabeceradatosfactura, lineacabeceradatosempresa, longitudmaximaproducto, longitudmaximadescripcion, longitudmaximanotas, cuadronotasl, cuadronotasr, escribehl, escribehu, paddinghueco, huecol, huecor, huecou, huecod, comienzolineaspgn, lineainicial, cuadrodireccionlr, cuadrodireccionud, lineassuma, tamano, curvatura, entrelineas, entresecciones, lastlinea, contadorlineas, poslr, lineaactual, posicionamiento[8]={20,76,191,370,432,479,524,584}, posicionamientosuma[6]={20,110,220,330,420,584}, desfasepossuma[5]={20,10,20,33,50};
  float opacidad, ventacantidad, ventaprecio, ventadescuento, ventaiva, preciolinea, sumacantidades, sumapreciobase, sumapreciototal, sumaiva, sumadto;

  //inicializacion de variables
  lineacabeceradatosfactura=50;
  lineacabeceradatosempresa=150;
  longitudmaximaproducto=20;
  longitudmaximadescripcion=34;
  longitudmaximanotas=103;
  cuadronotasl=45;
  cuadronotasr=550;
  escribehl=340;
  escribehu=180;
  paddinghueco=45;
  huecol=340-paddinghueco;
  huecor=538+paddinghueco;
  huecou=170-paddinghueco;
  huecod=226+paddinghueco;
  comienzolineaspgn=50;
  lineainicial=huecod+50;
  cuadrodireccionlr=310;
  cuadrodireccionud=150;
  lineassuma=750;
  tamano=10;
  opacidad=0.3;
  curvatura=5;
  entrelineas=25;
  entresecciones=35;
  ventacantidad=0.0;
  ventaprecio=0.0;
  ventadescuento=0.0;
  ventaiva=0.0;
  preciolinea=0.0;
  sumacantidades=0.0;
  sumapreciobase=0.0;
  sumapreciototal=0.0;
  sumaiva=0.0;
  sumadto=0.0;
  lineaactual=lineainicial;
  lastlinea=0;
  contadorlineas=1;
  poslr=0;
  contador=0;
  sprintf(outdir,"../temporales/");
  sprintf(workingname,"swap");
  sprintf(workingext,".jpg");
  sprintf(inputfile,"planfra.jpg");
  sprintf(inputfileb,"planfra2.jpg");
  sprintf(inputfilec,"planfra3.jpg");
  sprintf(fuente,"Helvetica");
  sprintf(color,"red");
  sprintf(colorsumas,"black");
  sprintf(colortitulossumas,"white");
  sprintf(colorcabecera,"tomato4");
  sprintf(colortitulos,"DarkOliveGreen");
  sprintf(colorproductosimpar,"red");
  sprintf(colorproductospar,"blue");
  sprintf(colorcorrespondencia,"black");
  sprintf(fondopar,"red");
  sprintf(fondoimpar,"blue");
  sprintf(fondotitulos,"gold");
  sprintf(fondotitulossuma,"green");
  sprintf(fondonotas,"aqua");
  sprintf(fondosumas,"orchid4");
  sprintf(fondohueco,"aqua");
  sprintf(usuario,"su_usuario");
  sprintf(database,"su_basededatos");
  sprintf(contras,"su_contras");
  sprintf(empresanombre,"su_empresa");
  sprintf(empresacif,"su_cif");
  sprintf(empresadireccion,"su_direccion");
  sprintf(empresacp,"su_cp");
  sprintf(empresalocalidad,"su_localidad");
  sprintf(empresaprovincia,"su_provincia");
  sprintf(empresatelefono,"su_telefono");
  sprintf(empresaweb,"su_web");
  sprintf(empresamail,"su_mail");
  sprintf(empresalogo,"logo.jpg");
  sprintf(workingfile,"%d%s%s",contador,workingname,workingext);
  sprintf(idfra,argv[1]);
  if (argc>1){
    sprintf(sendmails,argv[2]);
  }

  mysql= mysql_init(NULL);
  if (!mysql_real_connect(mysql, "localhost", usuario, contras, database,0,NULL,0)) show_error(mysql);

  sprintf(consulta,"SELECT FVenta.NFra, FVenta.Fecha, FVenta.Notas, Cliente.Nombre, Cliente.Apellidos, Cliente.DNI, Cliente.Direccion, Cliente.Cp, Cliente.Poblacion, Cliente.Provincia, Cliente.Mail FROM FVenta INNER JOIN Cliente ON FVenta.Cliente=Cliente.Id WHERE FVenta.Id=%s",idfra);
  query=consulta;
  if (mysql_real_query(mysql, query, strlen(query))) show_error(mysql);
  result= mysql_store_result(mysql);
  totalrows = mysql_num_rows(result);
  numfields = mysql_num_fields(result);
  a=1;
  while(row = mysql_fetch_row(result))
  {
    for(i = 0; i < numfields; i++)
    {
        char *val = row[i];
	switch(i){
	  case 0:
	    sprintf(clientenfra,"%s",row[i]);
	    break;
	  case 1:
	    sprintf(clientefecha,"%c%c/%c%c/%c%c%c%c",row[i][8],row[i][9],row[i][5],row[i][6],row[i][0],row[i][1],row[i][2],row[i][3]);
	    break;
	  case 2:
	    sprintf(clientenotas,"%s ",row[i]);
	    break;
	  case 3:
	    sprintf(clientenombre,"%s",row[i]);
	    break;
	  case 4:
	    sprintf(clienteapellidos,"%s",row[i]);
	    break;
	  case 5:
	    sprintf(clientedni,"%s",row[i]);
	    break;
	  case 6:
	    sprintf(clientedireccion,"%s",row[i]);
	    break;
	  case 7:
	    sprintf(clientecp,"%s",row[i]);
	    break;
	  case 8:
	    sprintf(clientepoblacion,"%s",row[i]);
	    break;
	  case 9:
	    sprintf(clienteprovincia,"%s",row[i]);
	    break;
	  case 10:
	    sprintf(clientemail,"%s",row[i]);
	    break;
	  default:
	    printf("Diferentes registros E/S\n");
	}
    }
    a++;
  }
  mysql_free_result(result);


  sprintf(orden,"convert %s %s",inputfile,workingfile);
  system(orden);
  sprintf(outfile,"F%s.pdf",clientenfra);
  sprintf(outputfile,"%s%s",outdir,outfile);

  
  //TODO CABECERA
  sprintf(color,"%s",colorcorrespondencia);
  sprintf(orden,"convert -font %s -fill %s -pointsize %d -draw \"text %d,%d '%s %s'\" %s %s",fuente,color,tamano,escribehl,escribehu,clientenombre,clienteapellidos,workingfile,workingfile);
  system(orden);
  escribehu+=15;
  sprintf(orden,"convert -font %s -fill %s -pointsize %d -draw \"text %d,%d '%s'\" %s %s",fuente,color,tamano,escribehl,escribehu,clientedireccion,workingfile,workingfile);
  system(orden);
  escribehu+=15;
  sprintf(orden,"convert -font %s -fill %s -pointsize %d -draw \"text %d,%d '%s %s'\" %s %s",fuente,color,tamano,escribehl,escribehu,clientecp,clientepoblacion,workingfile,workingfile);
  system(orden);
  escribehu+=15;
  sprintf(orden,"convert -font %s -fill %s -pointsize %d -draw \"text %d,%d '(%s)'\" %s %s",fuente,color,tamano,escribehl,escribehu,clienteprovincia,workingfile,workingfile);
  system(orden);
  lineaactual=lineacabeceradatosfactura;
  poslr=30;
  sprintf(color,"%s",colorcabecera);
  sprintf(orden,"convert -font %s -fill %s -pointsize %d -draw \"text %d,%d 'Factura N.: %s          Fecha: %s'\" %s %s",fuente,color,tamano,poslr,lineaactual,clientenfra,clientefecha,workingfile,workingfile);
  system(orden);
  lineaactual+=20;
  sprintf(orden,"convert -font %s -fill %s -pointsize %d -draw \"text %d,%d 'DNI: %s   Cliente: %s %s'\" %s %s",fuente,color,tamano,poslr,lineaactual,clientedni,clientenombre,clienteapellidos,workingfile,workingfile);
  system(orden);
 
  lineainicial+=entrelineas;
  
  sumapreciobase=0.0;
  sumacantidades=0.0;
  sumaiva=0.0;
  sumadto=0.0;
  sumapreciototal=0.0;

  sprintf(consulta,"SELECT ROUND(Venta.Cantidad,2) AS Cantidad, Producto.Producto, Producto.Descripcion AS DescrPro, Venta.Descripcion AS DescrVent, ROUND(Venta.Precio,2) AS Precio, ROUND(Venta.Descuento,1) AS Descuento, ROUND(Venta.IVA,1) AS IVA FROM Venta INNER JOIN Producto ON Venta.Producto=Producto.Id WHERE Venta.Venta=%s",idfra);
  query=consulta;
  if (mysql_real_query(mysql, query, strlen(query))) show_error(mysql);
  result= mysql_store_result(mysql);
  totalrows = mysql_num_rows(result);
  numfields = mysql_num_fields(result);
  a=1;
  while(row = mysql_fetch_row(result))
  {
    for(i = 0; i < numfields; i++)
    {
        char *val = row[i];
	switch(i){
	  case 0:
	    ventacantidad=atof(row[i]);
	    break;
	  case 1:
	    sprintf(ventaproducto,"%s ",row[i]);
	    break;
	  case 2:
	    sprintf(ventadescripcion,"%s ",row[i]);
	    break;
	  case 3:
	    if(strcmp(row[i],"\0") != 0) sprintf(ventadescripcion,"%s ",row[i]);
	    break;
	  case 4:
	    ventaprecio=atof(row[i]);
	    break;
	  case 5:
	    ventadescuento=atof(row[i]);
	    break;
	  case 6:
	    ventaiva=atof(row[i]);
	    break;
	  default:
	    printf("Diferentes registros E/S\n");
	}//switch
    }//for

    preciolinea=redondeados(ventacantidad*ventaprecio*(1-ventadescuento/100)*(1+ventaiva/100));
    vuelta=0;
    while(strlen(ventaproducto)>0){
      char *swap;
      int largo,sublargo,espacio;
      int fin=findecadena(ventaproducto,longitudmaximaproducto);
      espacio=ultimoespacio(ventaproducto,longitudmaximaproducto);
      if(fin<=longitudmaximaproducto){
        for(x=0;x<espacio;x++)listaproducto[vuelta][x]=ventaproducto[x];
        listaproducto[vuelta][espacio]='\0';
	ventaproducto[0]='\0';
      } else {
        swap=ventaproducto;
        largo=strlen(ventaproducto);
        sublargo=largo-espacio-1;
        for(x=0;x<espacio;x++) listaproducto[vuelta][x]=swap[x];
        listaproducto[vuelta][espacio]='\0';
	for(x=0;x<sublargo;x++) ventaproducto[x]=swap[x+espacio+1];
        ventaproducto[sublargo]='\0';
      }
      vuelta++;
    }//while listaproducto
    longitudproducto=vuelta;
    vuelta=0;
    while(strlen(ventadescripcion)){
      char *swap;
      int largo,sublargo,espacio;
      int fin=findecadena(ventadescripcion,longitudmaximadescripcion);
      espacio=ultimoespacio(ventadescripcion,longitudmaximadescripcion);
      if(fin<=longitudmaximadescripcion){
        for(x=0;x<espacio;x++)listadescripcion[vuelta][x]=ventadescripcion[x];
        listadescripcion[vuelta][espacio]='\0';
	ventadescripcion[0]='\0';
      } else {
        swap=ventadescripcion;
        largo=strlen(ventadescripcion);
        sublargo=largo-espacio-1;
        for(x=0;x<espacio;x++) listadescripcion[vuelta][x]=swap[x];
        listadescripcion[vuelta][espacio]='\0';
	for(x=0;x<sublargo;x++) ventadescripcion[x]=swap[x+espacio+1];
        ventadescripcion[sublargo]='\0';
	}
      vuelta++;
    }//while listadescripcion
    longituddescripcion=vuelta;

    if(lineaactual+maximoentero(longitudproducto,longituddescripcion)*10>=lineassuma-entresecciones){
      //TODO VALORES HASTA EL MOMENTO AL FINAL DE LA PAGINA
      sumafinal(posicionamientosuma,lineassuma,workingfile,tamano,fuente,colorsumas,sumacantidades,sumapreciobase,sumadto,sumaiva,sumapreciototal);
      contador++;
      sprintf(workingfile,"%d%s%s",contador,workingname,workingext);
      sprintf(orden,"convert %s %s",inputfileb,workingfile);
      system(orden);
      lineainicial=comienzolineaspgn+entrelineas;
      lineaactual=lineainicial;
      lastlinea=0;
      eiud=lineainicial-15;
      lud=lineainicial+5;
    }//if cambio de pagina
    sumapreciobase=redondeados(sumapreciobase+(ventacantidad*ventaprecio*(1+ventadescuento/100)));
    sumacantidades=redondeados(sumacantidades+ventacantidad);
    sumaiva=redondeados(sumaiva+ventacantidad*ventaprecio*(1-ventadescuento/100)*ventaiva/100);
    sumadto=redondeados(sumadto+ventacantidad*ventaprecio*ventadescuento/100);
    sumapreciototal=redondeados(sumapreciobase+sumaiva);
    if(contadorlineas%2==0){
      sprintf(fondo,"%s",fondopar);
      sprintf(color,"%s",colorproductospar);
    } else {
      sprintf(fondo,"%s",fondoimpar);
      sprintf(color,"%s",colorproductosimpar);
    }
    eiud=lineainicial-15;
    lud=lineainicial+maximoentero(longitudproducto,longituddescripcion)*10-5;
    for(x=0;x<7;x++){
      sprintf(orden,"convert -fill %s -draw \"fill-opacity %.2f roundrectangle %d,%d %d,%d %d,%d\" %s %s",fondo,opacidad,posicionamiento[x]-5,eiud,posicionamiento[x+1]-10,lud,curvatura,curvatura,workingfile,workingfile);
      system(orden);
    }
    lineaactual=lineainicial;
    if(longitudproducto>=longituddescripcion){
      unilinea=lineainicial+(longitudproducto-1)*5;
      medlinea=redondearint(lineainicial+((longitudproducto-longituddescripcion)/2)*10);
      poslr=posicionamiento[1];
      for(x=0;x<longitudproducto;x++){
	sprintf(orden,"convert -font %s -fill %s -pointsize %d -draw \"text %d,%d '%s'\" %s %s",fuente,color,tamano,poslr,lineaactual,listaproducto[x],workingfile,workingfile);
	system(orden);
	lastlinea=maximoentero(lineaactual,lastlinea);
	lineaactual+=10;
      }
      poslr=posicionamiento[2];
      lineaactual=medlinea;
      for(x=0;x<longituddescripcion;x++){
	sprintf(orden,"convert -font %s -fill %s -pointsize %d -draw \"text %d,%d '%s'\" %s %s",fuente,color,tamano,poslr,lineaactual,listadescripcion[x],workingfile,workingfile);
	system(orden);
	lastlinea=maximoentero(lineaactual,lastlinea);
	lineaactual+=10;
      }
    } else {
      unilinea=lineainicial+(longituddescripcion-1)*5;
      medlinea=redondearint(lineainicial+((longituddescripcion-longitudproducto)/2)*10);
      poslr=posicionamiento[2];
      for(x=0;x<longituddescripcion;x++){
	sprintf(orden,"convert -font %s -fill %s -pointsize %d -draw \"text %d,%d '%s'\" %s %s",fuente,color,tamano,poslr,lineaactual,listadescripcion[x],workingfile,workingfile);
	system(orden);
	lastlinea=maximoentero(lineaactual,lastlinea);
	lineaactual+=10;
      }
      poslr=posicionamiento[1];
      lineaactual=medlinea;
      for(x=0;x<longitudproducto;x++){
	sprintf(orden,"convert -font %s -fill %s -pointsize %d -draw \"text %d,%d '%s'\" %s %s",fuente,color,tamano,poslr,lineaactual,listaproducto[x],workingfile,workingfile);
	system(orden);
	lastlinea=maximoentero(lineaactual,lastlinea);
	lineaactual+=10;
      }	
    }//ifelse
    poslr=posicionamiento[0]+10;
    if(ventacantidad<100){
      poslr+=6;
      if(ventacantidad<10){
	poslr+=6;
      }
    }
    sprintf(orden,"convert -font %s -fill %s -pointsize %d -draw \"text %d,%d '%.2f'\" %s %s",fuente,color,tamano,poslr,unilinea,ventacantidad,workingfile,workingfile);
    system(orden);
    poslr=posicionamiento[3]+10;
    if(ventaprecio<1000){
      poslr+=5;
      if(ventaprecio<100){
	poslr+=5;
	if(ventaprecio<10){
	  poslr+=5;
	}
      }
    }
    sprintf(orden,"convert -font %s -fill %s -pointsize %d -draw \"text %d,%d '%.2f'\" %s %s",fuente,color,tamano,poslr,unilinea,ventaprecio,workingfile,workingfile);
    system(orden);
    poslr=posicionamiento[4];
    if(ventadescuento<10){
      poslr+=5;
    }
    if(ventadescuento>0){
      sprintf(orden,"convert -font %s -fill %s -pointsize %d -draw \"text %d,%d '%.2f'\" %s %s",fuente,color,tamano,poslr,unilinea,ventadescuento,workingfile,workingfile);
      system(orden);
    }
    poslr=posicionamiento[5];
    if(ventaiva<10){
      poslr+=5;
    }
    sprintf(orden,"convert -font %s -fill %s -pointsize %d -draw \"text %d,%d '%.2f'\" %s %s",fuente,color,tamano,poslr,unilinea,ventaiva,workingfile,workingfile);
    system(orden);
    poslr=posicionamiento[6];
    if(preciolinea<10000){
      poslr+=5;
      if(preciolinea<1000){
	poslr+=5;
	if(preciolinea<100){
	  poslr+=5;
	  if(preciolinea<10){
	    poslr+=5;
	  }
	}
      }
    }
    sprintf(orden,"convert -font %s -fill %s -pointsize %d -draw \"text %d,%d '%.2f'\" %s %s",fuente,color,tamano,poslr,unilinea,preciolinea,workingfile,workingfile);
    system(orden);
    lineaactual=lastlinea+entrelineas;
    lineainicial=lineaactual;
    contadorlineas++;
    a++;
  }
  mysql_free_result(result);

  //COMENTARIOS FACTURA
  lineaactual+=entresecciones;
  poslr=50;
  vuelta=0;
  sprintf(fondo,"%s",fondonotas);
  while(strlen(clientenotas)){
    char *swap;
    int largo,sublargo,espacio;
    int fin=findecadena(clientenotas,longitudmaximanotas);
    espacio=ultimoespacio(clientenotas,longitudmaximanotas);
    if(fin<=longitudmaximanotas){
      for(x=0;x<espacio;x++)listanotas[vuelta][x]=clientenotas[x];
      listanotas[vuelta][espacio]='\0';
      clientenotas[0]='\0';
    } else {
      swap=clientenotas;
      largo=strlen(clientenotas);
      sublargo=largo-espacio-1;
      for(x=0;x<espacio;x++) listanotas[vuelta][x]=swap[x];
      listanotas[vuelta][espacio]='\0';
      for(x=0;x<sublargo;x++) clientenotas[x]=swap[x+espacio+1];
      clientenotas[sublargo]='\0';
      }
    vuelta++;
  }//while listanotas
  if(lineaactual+vuelta*10>=lineassuma-35){
    sumafinal(posicionamientosuma,lineassuma,workingfile,tamano,fuente,colorsumas,sumacantidades,sumapreciobase,sumadto,sumaiva,sumapreciototal);
    contador++;
    sprintf(workingfile,"%d%s%s",contador,workingname,workingext);
    sprintf(orden,"convert %s %s",inputfilec,workingfile);
    system(orden);
    lineainicial=comienzolineaspgn;
    lineaactual=lineainicial;
  }
  sprintf(orden,"convert -fill %s -draw \"fill-opacity %.2f roundrectangle %d,%d %d,%d %d,%d\" %s %s",fondo,opacidad,cuadronotasl,lineaactual-15,cuadronotasr,lineaactual+vuelta*10-5,curvatura,curvatura,workingfile,workingfile);
  system(orden);
  for(x=0;x<vuelta;x++){
    sprintf(orden,"convert -font %s -fill %s -pointsize %d -draw \"text %d,%d '%s'\" %s %s",fuente,color,tamano,cuadronotasl+5,lineaactual,listanotas[x],workingfile,workingfile);
    system(orden);
    lineaactual+=10;
  }


  //sumas
  sumafinal(posicionamientosuma,lineassuma,workingfile,tamano,fuente,colorsumas,sumacantidades,sumapreciobase,sumadto,sumaiva,sumapreciototal);

  //TODO INFO ABAJO
  contador++;
  for(x=0;x<contador;x++){
    sprintf(workingfile,"%d%s%s",x,workingname,workingext);
    if(x==0){
      sprintf(orden,"convert %s %s",workingfile,outputfile);
    } else {
      sprintf(orden,"convert %s swap.pdf",workingfile);
      system(orden);
      sprintf(orden,"pdfunite %s swap.pdf join.pdf",outputfile);
      system(orden);
      sprintf(orden,"mv join.pdf %s",outputfile);
      system(orden);
      sprintf(orden,"rm swap.pdf");
    }
    system(orden);
    sprintf(orden,"rm %s",workingfile);
    system(orden);
  }

  sprintf(clienteasunto,"%s Factura F%s",empresanombre,clientenfra);
  sprintf(clientecuerpo,"Estimado cliente, según nos pidió le mandamos la factura");

  if(argc==3){
    char cadena[5];
    sprintf(cadena,"%s",argv[2]);
    if(cadena[0]=='C'){
      if(clientemail) printf("C: Mandar correo al cliente\n");
    }
    if(cadena[0]=='A'){
      if(clientemail) printf("A: Mandar correo al cliente\n");
      printf("Mandar correo a mi\n");
    }
    if(cadena[0]=='Y'){
      printf("Mandar correo a mi\n");
    }
  }

  printf("%s",outfile);

  return 0;
}
