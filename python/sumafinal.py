import os
def SumandoVoy(PosicionamientoSuma, LineasSuma, WorkingFile, Tamano, Fuente, Color, SumaCantidades, SumaPrecioBase, SumaDto, SumaIVA, SumaPrecioTotal):
    LineaActual=LineasSuma
    for x in range(len(PosicionamientoSuma)-1):
        PosLR=PosicionamientoSuma[x]+30
        Valores=0
        if x == 0:
            Valores=SumaCantidades
            if SumaCantidades < 100:
                PosLR+=6
                if SumaCantidades < 10:
                    PosLR+=6
        elif x==1:
            Valores=SumaPrecioBase
            if SumaPrecioBase < 10000:
                PosLR+=5
                if SumaPrecioBase < 1000:
                    PosLR+=5
                    if SumaPrecioBase < 100:
                        PosLR+=5
                        if SumaPrecioBase < 10:
                            PosLR+=5
        elif x==2:
            Valores=SumaDto
            if SumaDto < 1000:
                PosLR+=5
                if SumaDto < 100:
                    PosLR+=5
                    if SumaDto < 10:
                        PosLR+=5
        elif x==3:
            Valores=SumaIVA
            if SumaIVA < 1000:
                PosLR+=5
                if SumaIVA < 100:
                    PosLR+=5
                    if SumaIVA < 10:
                        PosLR+=5
        elif x==4:
            Valores=SumaPrecioTotal
            PosLR+=30
            if SumaPrecioTotal < 100000:
                PosLR+=5
                if SumaPrecioTotal < 10000:
                    PosLR+=5
                    if SumaPrecioTotal < 1000:
                        PosLR+=5
                        if SumaPrecioTotal < 100:
                            PosLR+=5
                            if SumaPrecioTotal < 10:
                                PosLR+=5
        comando="convert -font "+Fuente+" -fill "+Color+" -pointsize "+str(Tamano)+" -draw \"text "+str(PosLR)+","+str(LineasSuma+30)+" '"+str(Valores)+"'\" "+WorkingFile+" "+WorkingFile
        os.system(comando)
