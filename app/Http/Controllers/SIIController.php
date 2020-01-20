<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SoapClient;
use DOMDocument;

class SIIController extends Controller
{

    public function setDePruebas()
    {
        \sasco\LibreDTE\Sii::setAmbiente(\sasco\LibreDTE\Sii::CERTIFICACION);
        // respuesta en texto plano
        header('Content-type: text/plain; charset=ISO-8859-1');
        // primer folio a usar para envio de set de pruebas
        $folios = [
            39 => 1, // boleta electrónica
            //61 => 1, // nota de crédito electrónicas
            //41 => 1, // boleta electronica exenta
        ];
        // caratula para el envío de los dte
        $caratula = [
            //'RutEnvia' => '11222333-4', // se obtiene automáticamente de la firma
            'RutReceptor' => '60803000-K',
            'FchResol' => '2018-09-10',
            'NroResol' => 0,
        ];
        // datos del emisor
        $Emisor = [
            'RUTEmisor' => '76747632-9',
            'RznSoc' => 'INFORMATICA FERNANDO ROMERO RODRIGUEZ E.I.R.L.', // tag verdadero es RznSocEmisor, pero se permite usar el de DTE
            'GiroEmis' => 'Servicios integrales de informática', // tag verdadero es GiroEmisor, pero se permite usar el de DTE
            'Acteco' => 726000, // en boleta este tag no va y se quita al normalizar (se deja para nota de crédito)
            'DirOrigen' => 'San Carlos',
            'CmnaOrigen' => 'San Carlos',
        ];
        // datos el recepor
        $Receptor = [
            'RUTRecep' => '55666777-8',
            'RznSocRecep' => 'Cliente S.A.',
            'DirRecep' => 'Santiago',
            'CmnaRecep' => 'Santiago',
        ];
        // datos de las boletas (cada elemento del arreglo $set_pruebas es una boleta)
        $set_pruebas = [
            // CASO 1
            [
                'Encabezado' => [
                    'IdDoc' => [
                        'TipoDTE' => 39,
                        'Folio' => $folios[39],
                    ],
                    'Emisor' => $Emisor,
                    'Receptor' => $Receptor,
                ],
                'Detalle' => [
                    [
                        'NmbItem' => 'Cambio de aceite',
                        'QtyItem' => 1,
                        'PrcItem' => 19900,
                    ],
                    [
                        'NmbItem' => 'Alineacion y balanceo',
                        'QtyItem' => 1,
                        'PrcItem' => 9900,
                    ],
                ],
            ],
            // CASO 2
            [
                'Encabezado' => [
                    'IdDoc' => [
                        'TipoDTE' => 39,
                        'Folio' => $folios[39]+1,
                    ],
                    'Emisor' => $Emisor,
                    'Receptor' => $Receptor,
                ],
                'Detalle' => [
                    [
                        'NmbItem' => 'Papel de regalo',
                        'QtyItem' => 17,
                        'PrcItem' => 120,
                    ],
                ],
            ],
            // CASO 3
    [
        'Encabezado' => [
            'IdDoc' => [
                'TipoDTE' => 39,
                'Folio' => $folios[39]+2,
            ],
            'Emisor' => $Emisor,
            'Receptor' => $Receptor,
        ],
        'Detalle' => [
            [
                'NmbItem' => 'Sandwich',
                'QtyItem' => 2,
                'PrcItem' => 1500,
            ],
            [
                'NmbItem' => 'Bebida',
                'QtyItem' => 2,
                'PrcItem' => 550,
            ],
        ],
    ],
    // CASO 4
    [
        'Encabezado' => [
            'IdDoc' => [
                'TipoDTE' => 39,
                'Folio' => $folios[39]+3,
            ],
            'Emisor' => $Emisor,
            'Receptor' => $Receptor,
        ],
        'Detalle' => [
            [
                'NmbItem' => 'item afecto 1',
                'QtyItem' => 8,
                'PrcItem' => 1590,
            ],
            [
                'IndExe' => 1,
                'NmbItem' => 'item exento 2',
                'QtyItem' => 2,
                'PrcItem' => 1000,
            ],
        ],
    ],
    // CASO 5
    [
        'Encabezado' => [
            'IdDoc' => [
                'TipoDTE' => 39,
                'Folio' => $folios[39]+4,
            ],
            'Emisor' => $Emisor,
            'Receptor' => $Receptor,
        ],
        'Detalle' => [
            [
                'NmbItem' => 'Arroz',
                'QtyItem' => 5,
                'PrcItem' => 700,
            ],
        ],
    ],
];
// Objetos de Firma y Folios
$firma_config = ['file'=>'./cert/76747632/firma.p12', 'pass'=>5690];
$Firma = new \sasco\LibreDTE\FirmaElectronica($firma_config);
$Folios = [];
foreach ($folios as $tipo => $cantidad)
    $Folios[$tipo] = new \sasco\LibreDTE\Sii\Folios(file_get_contents('./xml/folios/76747632/'.$tipo.'.xml'));
// generar cada DTE, timbrar, firmar y agregar al sobre de EnvioBOLETA
$EnvioDTE = new \sasco\LibreDTE\Sii\EnvioDte();
foreach ($set_pruebas as $documento) {
    $DTE = new \sasco\LibreDTE\Sii\Dte($documento);
    if (!$DTE->timbrar($Folios[$DTE->getTipo()]))
        break;
    if (!$DTE->firmar($Firma))
        break;
    $EnvioDTE->agregar($DTE);
}
$EnvioDTE->setFirma($Firma);
$EnvioDTE->setCaratula($caratula);
$EnvioDTE->generar();
if ($EnvioDTE->schemaValidate()) {
    if (is_writable('./xml/76747632/boletas.xml'))
        file_put_contents('./xml/76747632/boletas.xml', $EnvioDTE->generar()); // guardar XML en sistema de archivos
    echo $EnvioDTE->generar();
}

// si hubo errores mostrar
foreach (\sasco\LibreDTE\Log::readAll() as $error)
{
    echo $error."\n";
}

}
    
    public function ConsumoFolios()
    {
        header('Content-type: text/plain; charset=ISO-8859-1');
        // incluir archivos php de la biblioteca y configuraciones
        \sasco\LibreDTE\Sii::setAmbiente(\sasco\LibreDTE\Sii::CERTIFICACION);
        // archivos
        $boletas = './xml/76747632/boletas.xml';
        //$notas_credito = './xml/EnvioDTE.xml';
        // cargar XML boletas y notas
        $EnvioBOLETA = new \sasco\LibreDTE\Sii\EnvioDte();
        $EnvioBOLETA->loadXML(file_get_contents($boletas));
        // crear objeto para consumo de folios
        $ConsumoFolio = new \sasco\LibreDTE\Sii\ConsumoFolio();
        $firma_config = ['file'=>'./cert/76747632/firma.p12', 'pass'=>5690];
        $ConsumoFolio->setFirma(new \sasco\LibreDTE\FirmaElectronica($firma_config));
        $ConsumoFolio->setDocumentos([39, 41, 61]); // [39, 61] si es sólo afecto, [41, 61] si es sólo exento
        // agregar detalle de boletas
        foreach ($EnvioBOLETA->getDocumentos() as $Dte) {
            $ConsumoFolio->agregar($Dte->getResumen());
        }
        // crear carátula para el envío (se hace después de agregar los detalles ya que
        // así se obtiene automáticamente la fecha inicial y final de los documentos)
        $CaratulaEnvioBOLETA = $EnvioBOLETA->getCaratula();
        $ConsumoFolio->setCaratula([
            'RutEmisor' => $CaratulaEnvioBOLETA['RutEmisor'],
            'FchResol' => $CaratulaEnvioBOLETA['FchResol'],
            'NroResol' => $CaratulaEnvioBOLETA['NroResol'],
        ]);
        // generar, validar schema y mostrar XML
        $ConsumoFolio->generar();
        if ($ConsumoFolio->schemaValidate()) {
            echo $ConsumoFolio->generar();
            //$track_id = $ConsumoFolio->enviar();
            //var_dump($track_id);
        }
        // si hubo errores mostrar
        foreach (\sasco\LibreDTE\Log::readAll() as $error)
            echo $error,"\n";
    }
public function PDF_Boleta()
{
    header('Content-type: text/plain; charset=ISO-8859-1');
    // incluir archivos php de la biblioteca y configuraciones
    \sasco\LibreDTE\Sii::setAmbiente(\sasco\LibreDTE\Sii::CERTIFICACION);
    // sin límite de tiempo para generar documentos
    set_time_limit(0);
    // archivo XML de EnvioDTE que se generará
    $archivo = './xml/76747632/boletas.xml';
    //$archivo = 'xml/certificado/set_pruebas/set_pruebas_factura_exenta.xml';
    //$archivo = 'xml/certificado/etapa_simulacion.xml';
    // Cargar EnvioDTE y extraer arreglo con datos de carátula y DTEs
    $EnvioDte = new \sasco\LibreDTE\Sii\EnvioDte();
    $EnvioDte->loadXML(file_get_contents($archivo));
    $Caratula = $EnvioDte->getCaratula();
    $Documentos = $EnvioDte->getDocumentos();
    // directorio temporal para guardar los PDF
    $dir = sys_get_temp_dir().'/dte_'.$Caratula['RutEmisor'].'_'.$Caratula['RutReceptor'].'_'.str_replace(['-', ':', 'T'], '', $Caratula['TmstFirmaEnv']);
    if (is_dir($dir))
        \sasco\LibreDTE\File::rmdir($dir);
    if (!mkdir($dir))
        die('No fue posible crear directorio temporal para DTEs');
    // procesar cada DTEs e ir agregándolo al PDF
    foreach ($Documentos as $DTE) {
        if (!$DTE->getDatos())
            die('No se pudieron obtener los datos del DTE');
        $pdf = new \sasco\LibreDTE\Sii\dte\PDF\Dte(false); // =false hoja carta, =true papel contínuo (false por defecto si no se pasa)
        $pdf->setFooterText();
        $pdf->setLogo('./img/logos/76747632/logo.png'); // debe ser PNG!
        $pdf->setResolucion(['FchResol'=>$Caratula['FchResol'], 'NroResol'=>$Caratula['NroResol']]);
        //$pdf->setCedible(true);
        $pdf->agregar($DTE->getDatos(), $DTE->getTED());
        $pdf->Output($dir.'/dte_'.$Caratula['RutEmisor'].'_'.$DTE->getID().'.pdf', 'F');
    }
    // entregar archivo comprimido que incluirá cada uno de los DTEs
    \sasco\LibreDTE\File::compress($dir, ['format'=>'zip', 'delete'=>true]);
    }

}
