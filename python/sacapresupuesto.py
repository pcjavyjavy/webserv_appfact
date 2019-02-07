#!/usr/bin/python
# -*- coding: latin-1 -*-
import mysql.connector as mariadb
import sys, os, time
import sumafinal
from varprog import *

reload(sys)
sys.setdefaultencoding('latin-1')

MarcaFecha=str(time.time()).replace(".","")
InputFile="planfra.jpg"
InputFileB="planfra2.jpg"
InputFileC="planfra3.jpg"

WorkingFile=str(MarcaFecha)+str(Contador)+WorkingName+WorkingExt

comando="convert "+InputFile+" "+WorkingFile
os.system(comando)

mariadb_connection = mariadb.connect(user=Usuario, password=Contras, database=Database)
cursor = mariadb_connection.cursor()
cursor.execute("SELECT Presupuesto.NPres, Presupuesto.Fecha, Presupuesto.Validez, Presupuesto.Notas, Cliente.Nombre, Cliente.Apellidos, Cliente.DNI, Cliente.Direccion, Cliente.Cp, Cliente.Poblacion, Cliente.Provincia, Cliente.Mail FROM Presupuesto INNER JOIN Cliente ON Presupuesto.Cliente=Cliente.Id WHERE Presupuesto.Id=%s", (IDFra,))

for NFra, Fecha, Validez, Notas, Nombre, Apellidos, DNI, Direccion, Cp, Poblacion, Provincia, Mail in cursor:
    ClienteSwap=str(Fecha)
    ClienteFecha=ClienteSwap[8:10]+'/'+ClienteSwap[5:7]+'/'+ClienteSwap[0:4]
    ClienteSwap=str(Validez)
    ClienteValidez=ClienteSwap[8:10]+'/'+ClienteSwap[5:7]+'/'+ClienteSwap[0:4]
    ClienteDNI=DNI
    ClienteNombre=Nombre
    ClienteApellidos=Apellidos
    ClienteDireccion=Direccion
    ClienteCP=Cp
    ClientePoblacion=Poblacion
    ClienteProvincia=Provincia
    ClienteNFra=NFra
    ClienteNotas=Notas+' '
    ClienteMail=Mail

OutFile="P"+ClienteNFra+".pdf"
OutputFile=OutDir+OutFile

#TODO CABECERA
Color=ColorCorrespondencia
comando="convert -font "+Fuente+" -fill "+Color+" -pointsize "+str(Tamano)+" -draw \"text "+str(EscribeHL)+","+str(EscribeHU)+" '"+str(ClienteNombre.decode("latin-1").encode("utf-8")+' '+ClienteApellidos.decode("latin-1").encode("utf-8"))+"'\" "+WorkingFile+" "+WorkingFile
os.system(comando)
EscribeHU+=15
comando="convert -font "+Fuente+" -fill "+Color+" -pointsize "+str(Tamano)+" -draw \"text "+str(EscribeHL)+","+str(EscribeHU)+" '"+str(ClienteDireccion.decode("latin-1").encode("utf-8"))+"'\" "+WorkingFile+" "+WorkingFile
os.system(comando)
EscribeHU+=15
comando="convert -font "+Fuente+" -fill "+Color+" -pointsize "+str(Tamano)+" -draw \"text "+str(EscribeHL)+","+str(EscribeHU)+" '"+str(ClienteCP+' '+ClientePoblacion.decode("latin-1").encode("utf-8"))+"'\" "+WorkingFile+" "+WorkingFile
os.system(comando)
EscribeHU+=15
comando="convert -font "+Fuente+" -fill "+Color+" -pointsize "+str(Tamano)+" -draw \"text "+str(EscribeHL)+","+str(EscribeHU)+" '"+str('('+ClienteProvincia.decode("latin-1").encode("utf-8")+')')+"'\" "+WorkingFile+" "+WorkingFile
os.system(comando)

LineaActual=LineaCabeceraDatosFactura
PosLR=30
Color=ColorCabecera
comando="convert -font "+Fuente+" -fill "+Color+" -pointsize "+str(Tamano)+" -draw \"text "+str(PosLR)+","+str(LineaActual)+" '"+str('Presupuesto N.: '+ClienteNFra+'          Fecha: '+ClienteFecha)+"'\" "+WorkingFile+" "+WorkingFile
os.system(comando)
LineaActual+=20
comando="convert -font "+Fuente+" -fill "+Color+" -pointsize "+str(Tamano)+" -draw \"text "+str(PosLR)+","+str(LineaActual)+" '"+str('Valido hasta:  '+ClienteValidez)+"'\" "+WorkingFile+" "+WorkingFile
os.system(comando)
LineaActual+=20
comando="convert -font "+Fuente+" -fill "+Color+" -pointsize "+str(Tamano)+" -draw \"text "+str(PosLR)+","+str(LineaActual)+" '"+str('DNI: '+ClienteDNI+'   Cliente: '+ClienteNombre.decode("latin-1").encode("utf-8")+' '+ClienteApellidos.decode("latin-1").encode("utf-8"))+"'\" "+WorkingFile+" "+WorkingFile
os.system(comando)


LineaInicial+=EntreLineas
cursor.execute("SELECT ROUND(PresLineas.Cantidad,2) AS Cantidad, Producto.Producto, Producto.Descripcion AS DescrPro, PresLineas.Descripcion AS DescrVent, ROUND(PresLineas.Precio,2) AS Precio, ROUND(PresLineas.Descuento,1) AS Descuento, ROUND(PresLineas.IVA,1) AS IVA FROM PresLineas INNER JOIN Producto ON PresLineas.Producto=Producto.Id WHERE PresLineas.Presupuesto=%s", (IDFra,))

for Cantidad, Producto, DescrPro, DescrVent, Precio, Descuento, IVA in cursor:
    VentaCantidad=round(float(Cantidad),2)
    VentaProducto=Producto+' '
    if DescrVent == '':
        VentaDescripcion=DescrPro+' '
    else:
        VentaDescripcion=DescrVent+' '
    VentaPrecio=float(Precio)
    VentaDescuento=float(Descuento)
    VentaIVA=float(IVA)
    PrecioLinea=round(VentaCantidad*VentaPrecio*(1-VentaDescuento/100)*(1+VentaIVA/100),2)
    ListaProducto=[]
    ListaDescripcion=[]
    while not VentaProducto == '':
        swap=VentaProducto[0:LongitudMaximaProducto]
        fin=swap.rfind(" ")
        ListaProducto.append(VentaProducto[0:fin])
        fin=fin+1
        VentaProducto=VentaProducto[fin:]
    LongitudProducto=len(ListaProducto)
    while not VentaDescripcion == '':
        swap=VentaDescripcion[0:LongitudMaximaDescripcion]
        fin=swap.rfind(" ")
        ListaDescripcion.append(VentaDescripcion[0:fin])
        fin=fin+1
        VentaDescripcion=VentaDescripcion[fin:]
    LongitudDescripcion=len(ListaDescripcion)
    if LineaActual+max(LongitudProducto,LongitudDescripcion)*10 >= LineasSuma-50:
        # TODO VALORES HASTA EL MOMENTO AL FINAL DE PAGINA
        sumafinal.SumandoVoy(PosicionamientoSuma, LineasSuma, WorkingFile, Tamano, Fuente, ColorSumas, SumaCantidades, SumaPrecioBase, SumaDto, SumaIVA, SumaPrecioTotal)
        Contador+=1
        WorkingFile=str(MarcaFecha)+str(Contador)+WorkingName+WorkingExt
        LineaInicial=ComienzoLineasPgN
        LineaActual=LineaInicial
        LastLinea=0
        comando="convert "+InputFileB+" "+WorkingFile
        os.system(comando)
        EIUD=LineaInicial-15
        LUD=LineaInicial+5
        Fondo=FondoTitulos
        Color=ColorTitulos
        LineaInicial+=EntreLineas
        LineaActual=LineaInicial
    SumaPrecioBase=round(SumaPrecioBase+(VentaCantidad*VentaPrecio*(1-VentaDescuento/100)),2)
    SumaCantidades=round(SumaCantidades+VentaCantidad,2)
    SumaIVA=round(SumaIVA+VentaCantidad*VentaPrecio*(1-VentaDescuento/100)*VentaIVA/100,2)
    SumaDto=round(SumaDto+VentaCantidad*VentaPrecio*VentaDescuento/100,2)
    SumaPrecioTotal=round(SumaPrecioBase+SumaIVA,2)
    if ContadorLineas%2 == 0:
        Fondo=FondoPar
        Color=ColorProductosPar
    else:
        Fondo=FondoImpar
        Color=ColorProductosImpar
    EIUD=LineaInicial-15
    LUD=LineaInicial+max(LongitudProducto,LongitudDescripcion)*10-5
    for x in range(len(Titulos)):
        comando="convert -fill "+Fondo+" -draw \"fill-opacity "+str(Opacidad)+" roundrectangle "+str(Posicionamiento[x]-5)+","+str(EIUD)+" "+str(Posicionamiento[x+1]-10)+","+str(LUD)+" "+str(Curvatura)+","+str(Curvatura)+"\" "+WorkingFile+" "+WorkingFile
        os.system(comando)
    LineaActual=LineaInicial
    if LongitudProducto >= LongitudDescripcion:
        UniLinea=LineaInicial+(LongitudProducto-1)*5
        MedLinea=int(round(LineaInicial+((LongitudProducto-LongitudDescripcion)/2.0)*10))
        PosLR=Posicionamiento[1]
        for x in ListaProducto:
            comando="convert -font "+Fuente+" -fill "+Color+" -pointsize "+str(Tamano)+" -draw \"text "+str(PosLR)+","+str(LineaActual)+" '"+str(x.decode("latin-1").encode("utf-8"))+"'\" "+WorkingFile+" "+WorkingFile
            os.system(comando)
            LastLinea=max(LineaActual,LastLinea)
            LineaActual=LineaActual+10
        PosLR=Posicionamiento[2]
        LineaActual=MedLinea
        for x in ListaDescripcion:
            comando="convert -font "+Fuente+" -fill "+Color+" -pointsize "+str(Tamano)+" -draw \"text "+str(PosLR)+","+str(LineaActual)+" '"+str(x.decode("latin-1").encode("utf-8"))+"'\" "+WorkingFile+" "+WorkingFile
            os.system(comando)
            LastLinea=max(LineaActual,LastLinea)
            LineaActual=LineaActual+10
    else:
        UniLinea=LineaInicial+(LongitudDescripcion-1)*5
        MedLinea=int(round(LineaInicial+((LongitudDescripcion-LongitudProducto)/2.0)*10))
        PosLR=Posicionamiento[2]
        for x in ListaDescripcion:
            comando="convert -font "+Fuente+" -fill "+Color+" -pointsize "+str(Tamano)+" -draw \"text "+str(PosLR)+","+str(LineaActual)+" '"+str(x.decode("latin-1").encode("utf-8"))+"'\" "+WorkingFile+" "+WorkingFile
            os.system(comando)
            LastLinea=max(LineaActual,LastLinea)
            LineaActual=LineaActual+10
        PosLR=Posicionamiento[1]
        LineaActual=MedLinea
        for x in ListaProducto:
            comando="convert -font "+Fuente+" -fill "+Color+" -pointsize "+str(Tamano)+" -draw \"text "+str(PosLR)+","+str(LineaActual)+" '"+str(x.decode("latin-1").encode("utf-8"))+"'\" "+WorkingFile+" "+WorkingFile
            os.system(comando)
            LastLinea=max(LineaActual,LastLinea)
            LineaActual=LineaActual+10
    PosLR=Posicionamiento[0]+10
    if VentaCantidad < 100:
        PosLR+=6
        if VentaCantidad < 10:
            PosLR+=6
    comando="convert -font "+Fuente+" -fill "+Color+" -pointsize "+str(Tamano)+" -draw \"text "+str(PosLR)+","+str(UniLinea)+" '"+str("%.2f" % VentaCantidad)+"'\" "+WorkingFile+" "+WorkingFile
    os.system(comando)
    PosLR=Posicionamiento[3]+10
    if VentaPrecio < 1000:
        PosLR+=5
        if VentaPrecio < 100:
            PosLR+=5
            if VentaPrecio < 10:
                PosLR+=5
    comando="convert -font "+Fuente+" -fill "+Color+" -pointsize "+str(Tamano)+" -draw \"text "+str(PosLR)+","+str(UniLinea)+" '"+str("%.2f" % VentaPrecio)+"'\" "+WorkingFile+" "+WorkingFile
    os.system(comando)
    PosLR=Posicionamiento[4]
    if VentaDescuento < 10:
        PosLR+=5
    if VentaDescuento > 0:
        comando="convert -font "+Fuente+" -fill "+Color+" -pointsize "+str(Tamano)+" -draw \"text "+str(PosLR)+","+str(UniLinea)+" '"+str(VentaDescuento)+"%'\" "+WorkingFile+" "+WorkingFile
        os.system(comando)
    PosLR=Posicionamiento[5]
    if VentaIVA < 10:
        PosLR+=5
    comando="convert -font "+Fuente+" -fill "+Color+" -pointsize "+str(Tamano)+" -draw \"text "+str(PosLR)+","+str(UniLinea)+" '"+str(VentaIVA)+"%'\" "+WorkingFile+" "+WorkingFile
    os.system(comando)
    PosLR=Posicionamiento[6]
    if PrecioLinea < 10000:
        PosLR+=5
        if PrecioLinea < 1000:
            PosLR+=5
            if PrecioLinea < 100:
                PosLR+=5
                if PrecioLinea < 10:
                    PosLR+=5
    comando="convert -font "+Fuente+" -fill "+Color+" -pointsize "+str(Tamano)+" -draw \"text "+str(PosLR)+","+str(UniLinea)+" '"+str("%.2f" % PrecioLinea)+"'\" "+WorkingFile+" "+WorkingFile
    os.system(comando)
    LineaActual=LastLinea+EntreLineas
    LineaInicial=LineaActual
    ContadorLineas+=1
    


    

#TODO comentarios factura
LineaActual=LineaActual+EntreSecciones
PosLR=50
ListaNotas=[]
Fondo=FondoNotas
if ClienteNotas== ' ':
    ClienteNotas=''
else:
    while not ClienteNotas == '':
        swap=ClienteNotas[0:LongitudMaximaNotas]
        fin=swap.rfind(" ")
        ListaNotas.append(ClienteNotas[0:fin])
        fin=fin+1
        ClienteNotas=ClienteNotas[fin:]
    LongitudNotas=len(ListaNotas)
    if LineaActual+LongitudNotas*10 >= LineasSuma-50:
        # TODO VALORES HASTA EL MOMENTO AL FINAL DE PAGINA
        sumafinal.SumandoVoy(PosicionamientoSuma, LineasSuma, WorkingFile, Tamano, Fuente, ColorSumas, SumaCantidades, SumaPrecioBase, SumaDto, SumaIVA, SumaPrecioTotal)
        Contador+=1
        WorkingFile=str(MarcaFecha)+str(Contador)+WorkingName+WorkingExt
        LineaInicial=ComienzoLineasPgN
        LineaActual=LineaInicial
        comando="convert "+InputFileC+" "+WorkingFile
        os.system(comando)
    comando="convert -fill "+Fondo+" -draw \"fill-opacity "+str(Opacidad)+" roundrectangle "+str(CuadroNotasL)+","+str(LineaActual-15)+" "+str(CuadroNotasR)+","+str(LineaActual+LongitudNotas*10-5)+" "+str(Curvatura)+","+str(Curvatura)+"\" "+WorkingFile+" "+WorkingFile
    os.system(comando)    
    for x in ListaNotas:
        comando="convert -font "+Fuente+" -fill "+Color+" -pointsize "+str(Tamano)+" -draw \"text "+str(CuadroNotasL+5)+","+str(LineaActual)+" '"+str(x.decode("latin-1").encode("utf-8"))+"'\" "+WorkingFile+" "+WorkingFile
        os.system(comando)
        LineaActual=LineaActual+10


mariadb_connection.close()


#TODO SUMAS
sumafinal.SumandoVoy(PosicionamientoSuma, LineasSuma, WorkingFile, Tamano, Fuente, ColorSumas, SumaCantidades, SumaPrecioBase, SumaDto, SumaIVA, SumaPrecioTotal)






#TODO INFO ABAJO
Contador+=1
for x in range(Contador):
    WorkingFile=str(MarcaFecha)+str(x)+WorkingName+WorkingExt
    if x == 0:
        comando="convert "+WorkingFile+" "+OutputFile
    else:
        comando="convert "+WorkingFile+" swap.pdf"
        os.system(comando)
        comando="pdfunite "+OutputFile+" swap.pdf join.pdf"
        os.system(comando)
        comando="mv join.pdf "+OutputFile
        os.system(comando)
        comando="rm swap.pdf"
    os.system(comando)
    comando="rm "+WorkingFile
    os.system(comando)

ClienteAsunto=str(EmpresaNombre.decode("latin-1").encode("utf-8"))+" Presupuesto P"+str(ClienteNFra)
EmpresaAsunto="Presupuesto P"+str(ClienteNFra)+" Cliente "+str(ClienteNombre.decode("latin-1").encode("utf-8"))+" "+str(ClienteApellidos.decode("latin-1").encode("utf-8"))
#Debug
ClienteMail="javyortega15@gmail.com"
EmpresaMail="informatica.vallelado@gmail.com"

if len(sys.argv)==3:
    if sys.argv[2]=='C':
        if not ClienteMail == '':
            #Mandar correo al cliente
            comando="sudo mutt -s \""+ClienteAsunto+"\" -a "+OutputFile+" -- "+ClienteMail+" < mensajep"
            os.system(comando)
    elif sys.argv[2]=='A':
        if not ClienteMail == '':
            #Mandar correo al cliente
            comando="sudo mutt -s \""+ClienteAsunto+"\" -a "+OutputFile+" -- "+ClienteMail+" < mensajep"
            os.system(comando)
        #Mandar correo a mi
        comando="sudo mutt -s \""+EmpresaAsunto+"\" -a "+OutputFile+" -- "+EmpresaMail+" < mensajepm"
        os.system(comando)
    elif sys.argv[2]=='Y':
        #Mandar correo a mi
        comando="sudo mutt -s \""+EmpresaAsunto+"\" -a "+OutputFile+" -- "+EmpresaMail+" < mensajepm"
        os.system(comando)


comando="sudo rm /root/sent"
os.system(comando)
print OutFile

