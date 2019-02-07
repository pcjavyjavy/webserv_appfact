# -*- coding: latin-1 -*-
import sys
reload(sys)
sys.setdefaultencoding('latin-1')


Usuario="su_usuario"
Database="su_basededatos"
Contras="su_contras"
LineaCabeceraDatosFactura=50
LineaCabeceraDatosEmpresa=150
EmpresaNombre="su_empresa"
EmpresaCIF="su_cif"
EmpresaDireccion="su_direccion"
EmpresaCP="su_cp"
EmpresaLocalidad="su_localidad"
EmpresaProvincia="su_provincia"
EmpresaTelefono="su_telefono"
EmpresaWeb="su_web"
EmpresaMail="su_mail"
EmpresaLogo="logo.jpg"
LongitudMaximaProducto=20
LongitudMaximaDescripcion=34
LongitudMaximaNotas=103
CuadroNotasL=45
CuadroNotasR=550
EscribeHL=340
EscribeHU=180
PaddingHueco=45
HuecoL=340-PaddingHueco
HuecoR=538+PaddingHueco
HuecoU=170-PaddingHueco
HuecoD=226+PaddingHueco
FondoPar="red"
FondoImpar="blue"
FondoTitulos="gold"
FondoTitulosSuma="green"
FondoNotas="aqua"
FondoSumas="orchid4"
FondoHueco="aqua"
ComienzoLineasPgN=50
LineaInicial=HuecoD+50
CuadroDireccionLR=310
CuadroDireccionUD=150
LineasSuma=750
Fuente="Helvetica"
Color="red"
ColorSumas="black"
ColorTitulosSumas="white"
ColorCabecera="tomato4"
ColorTitulos="DarkOliveGreen"
ColorProductosImpar="red"
ColorProductosPar="blue"
ColorCorrespondencia="black"
Tamano=10
Opacidad=0.3
Curvatura=5
EntreLineas=25
EntreSecciones=35
Posicionamiento=[20,76,191,370,432,479,524,584]
Titulos=["Cantidad","Producto","Descripción","Precio Ud.","Dto.","IVA","Precio"]
PosicionamientoSuma=[20,110,220,330,420,584]
DesfasePosSuma=[20,10,20,33,50]
TitulosSuma=["Cantidad","Base Imponible","Descuentos","IVA","PrecioTotal"]


InputFile="plantilla2.jpg"
InputFileB="plantilla.jpg"

WorkingName="swap"
WorkingExt=".jpg"

OutDir="../temporales/"
