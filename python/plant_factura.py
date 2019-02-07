#!/usr/bin/python
# -*- coding: latin-1 -*-
import sys, os
from varprog import *

reload(sys)
sys.setdefaultencoding('latin-1')

WorkingFile="planfra.jpg"
comando="convert "+InputFile+" "+WorkingFile
os.system(comando)

#FONDO CORRESPONDENCIA
Fondo=FondoHueco
comando="convert -fill "+Fondo+" -draw \"fill-opacity "+str(Opacidad)+" roundrectangle "+str(HuecoL)+","+str(HuecoU)+" "+str(HuecoR)+","+str(HuecoD)+" "+str(Curvatura)+","+str(Curvatura)+"\" "+WorkingFile+" "+WorkingFile
os.system(comando)


#INFO EMPRESA
LineaActual=LineaCabeceraDatosEmpresa
PosLR=30
comando="convert -font "+Fuente+" -fill "+Color+" -pointsize "+str(Tamano)+" -draw \"text "+str(PosLR)+","+str(LineaActual)+" '"+str(EmpresaNombre.decode("latin-1").encode("utf-8")+'        '+EmpresaCIF)+"'\" "+WorkingFile+" "+WorkingFile
os.system(comando)
LineaActual+=20
comando="convert -font "+Fuente+" -fill "+Color+" -pointsize "+str(Tamano)+" -draw \"text "+str(PosLR)+","+str(LineaActual)+" '"+str(EmpresaDireccion.decode("latin-1").encode("utf-8"))+"'\" "+WorkingFile+" "+WorkingFile
os.system(comando)
LineaActual+=20
comando="convert -font "+Fuente+" -fill "+Color+" -pointsize "+str(Tamano)+" -draw \"text "+str(PosLR)+","+str(LineaActual)+" '"+str(EmpresaCP+'   '+EmpresaLocalidad.decode("latin-1").encode("utf-8")+'   ('+EmpresaProvincia.decode("latin-1").encode("utf-8")+')')+"'\" "+WorkingFile+" "+WorkingFile
os.system(comando)
LineaActual+=20
comando="convert -font "+Fuente+" -fill "+Color+" -pointsize "+str(Tamano)+" -draw \"text "+str(PosLR)+","+str(LineaActual)+" '"+str('Telefono: '+EmpresaTelefono)+"'\" "+WorkingFile+" "+WorkingFile
os.system(comando)
LineaActual+=20
comando="convert -font "+Fuente+" -fill "+Color+" -pointsize "+str(Tamano)+" -draw \"text "+str(PosLR)+","+str(LineaActual)+" '"+str('Web: '+EmpresaWeb)+"'\" "+WorkingFile+" "+WorkingFile
os.system(comando)
LineaActual+=20
comando="convert -font "+Fuente+" -fill "+Color+" -pointsize "+str(Tamano)+" -draw \"text "+str(PosLR)+","+str(LineaActual)+" '"+str('Mail: '+EmpresaMail)+"'\" "+WorkingFile+" "+WorkingFile
os.system(comando)


#TITULOS PRODUCTOS
EIUD=LineaInicial-15
LUD=LineaInicial+5
ant=0
Fondo=FondoTitulos
Color=ColorTitulos
for x in range(len(Titulos)):
    comando="convert -fill "+Fondo+" -draw \"fill-opacity "+str(Opacidad)+" roundrectangle "+str(Posicionamiento[x]-5)+","+str(EIUD)+" "+str(Posicionamiento[x+1]-10)+","+str(LUD)+" "+str(Curvatura)+","+str(Curvatura)+"\" "+WorkingFile+" "+WorkingFile
    os.system(comando)
    comando="convert -font "+Fuente+" -fill "+Color+" -pointsize "+str(Tamano)+" -draw \"text "+str(Posicionamiento[x])+","+str(LineaInicial)+" '"+str(Titulos[x])+"'\" "+WorkingFile+" "+WorkingFile
    os.system(comando)

LineaActual=LineasSuma
Fondo=FondoTitulosSuma
for x in range(len(TitulosSuma)):
    comando="convert -fill "+Fondo+" -draw \"fill-opacity "+str(Opacidad)+" roundrectangle "+str(PosicionamientoSuma[x]-5)+","+str(LineasSuma)+" "+str(PosicionamientoSuma[x+1]-10)+","+str(LineasSuma+15)+" "+str(Curvatura)+","+str(Curvatura)+"\" "+WorkingFile+" "+WorkingFile
    os.system(comando)
    comando="convert -font "+Fuente+" -fill "+Color+" -pointsize "+str(Tamano)+" -draw \"text "+str(PosicionamientoSuma[x]+DesfasePosSuma[x])+","+str(LineasSuma+10)+" '"+str(TitulosSuma[x])+"'\" "+WorkingFile+" "+WorkingFile
    os.system(comando)
Fondo=FondoSumas
for x in range(len(TitulosSuma)):
    comando="convert -fill "+Fondo+" -draw \"fill-opacity "+str(Opacidad)+" roundrectangle "+str(PosicionamientoSuma[x]-5)+","+str(LineasSuma+20)+" "+str(PosicionamientoSuma[x+1]-10)+","+str(LineasSuma+35)+" "+str(Curvatura)+","+str(Curvatura)+"\" "+WorkingFile+" "+WorkingFile
    os.system(comando)

WorkingFile="planfra2.jpg"
LineaInicial=ComienzoLineasPgN
LineaActual=LineaInicial
comando="convert "+InputFileB+" "+WorkingFile
os.system(comando)
EIUD=LineaInicial-15
LUD=LineaInicial+5
Fondo=FondoTitulos
Color=ColorTitulos
for x in range(len(Titulos)):
    comando="convert -fill "+Fondo+" -draw \"fill-opacity "+str(Opacidad)+" roundrectangle "+str(Posicionamiento[x]-5)+","+str(EIUD)+" "+str(Posicionamiento[x+1]-10)+","+str(LUD)+" "+str(Curvatura)+","+str(Curvatura)+"\" "+WorkingFile+" "+WorkingFile
    os.system(comando)
    comando="convert -font "+Fuente+" -fill "+Color+" -pointsize "+str(Tamano)+" -draw \"text "+str(Posicionamiento[x])+","+str(LineaInicial)+" '"+str(Titulos[x])+"'\" "+WorkingFile+" "+WorkingFile
    os.system(comando)
LineaActual=LineasSuma
Fondo=FondoTitulosSuma
for x in range(len(TitulosSuma)):
    comando="convert -fill "+Fondo+" -draw \"fill-opacity "+str(Opacidad)+" roundrectangle "+str(PosicionamientoSuma[x]-5)+","+str(LineasSuma)+" "+str(PosicionamientoSuma[x+1]-10)+","+str(LineasSuma+15)+" "+str(Curvatura)+","+str(Curvatura)+"\" "+WorkingFile+" "+WorkingFile
    os.system(comando)
    comando="convert -font "+Fuente+" -fill "+Color+" -pointsize "+str(Tamano)+" -draw \"text "+str(PosicionamientoSuma[x]+DesfasePosSuma[x])+","+str(LineasSuma+10)+" '"+str(TitulosSuma[x])+"'\" "+WorkingFile+" "+WorkingFile
    os.system(comando)
Fondo=FondoSumas
for x in range(len(TitulosSuma)):
    comando="convert -fill "+Fondo+" -draw \"fill-opacity "+str(Opacidad)+" roundrectangle "+str(PosicionamientoSuma[x]-5)+","+str(LineasSuma+20)+" "+str(PosicionamientoSuma[x+1]-10)+","+str(LineasSuma+35)+" "+str(Curvatura)+","+str(Curvatura)+"\" "+WorkingFile+" "+WorkingFile
    os.system(comando)


WorkingFile="planfra3.jpg"
comando="convert "+InputFileB+" "+WorkingFile
os.system(comando)
LineaActual=LineasSuma
Fondo=FondoTitulosSuma
for x in range(len(TitulosSuma)):
    comando="convert -fill "+Fondo+" -draw \"fill-opacity "+str(Opacidad)+" roundrectangle "+str(PosicionamientoSuma[x]-5)+","+str(LineasSuma)+" "+str(PosicionamientoSuma[x+1]-10)+","+str(LineasSuma+15)+" "+str(Curvatura)+","+str(Curvatura)+"\" "+WorkingFile+" "+WorkingFile
    os.system(comando)
    comando="convert -font "+Fuente+" -fill "+Color+" -pointsize "+str(Tamano)+" -draw \"text "+str(PosicionamientoSuma[x]+DesfasePosSuma[x])+","+str(LineasSuma+10)+" '"+str(TitulosSuma[x])+"'\" "+WorkingFile+" "+WorkingFile
    os.system(comando)
Fondo=FondoSumas
for x in range(len(TitulosSuma)):
    comando="convert -fill "+Fondo+" -draw \"fill-opacity "+str(Opacidad)+" roundrectangle "+str(PosicionamientoSuma[x]-5)+","+str(LineasSuma+20)+" "+str(PosicionamientoSuma[x+1]-10)+","+str(LineasSuma+35)+" "+str(Curvatura)+","+str(Curvatura)+"\" "+WorkingFile+" "+WorkingFile
    os.system(comando)
