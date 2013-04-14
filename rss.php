<?php

// Elimina caracteres extraños que me pueden molestar en las cadenas que meto en los item de los RSS
function clrAll($str) {
	$str=str_replace("&","&amp;",$str);
	$str=str_replace("\"","&quot;",$str);
	$str=str_replace("'","&apos;",$str);
	$str=str_replace(">","&gt;",$str);
	$str=str_replace("<","&lt;",$str);
	return $str;
}

//creo cabeceras desde PHP para decir que devuelvo un XML
header("Content-type: text/xml");

//comienzo a escribir el código del RSS
echo "<?xml version=\"1.0\""." encoding=\"ISO-8859-1\"?>";

//Conexión a la base de datos.


if ($conn_access = odbc_connect("Driver={SQL Server Native Client 10.0};Server=$server;Database=$database;", $user, $password))


{
	//echo "Conectado correctamente.<br>";
	$ssql = "
select ID, RAZ_SOCIAL, FECHA, IMP_TOTAL from ERP_AUT_VEN_COM where ESTADO = 'P' and FK_ERP_T_COM_VEN = 'PED'
";
	
if ($rs_access = odbc_exec ($conn_access, $ssql))
	{
//Cabeceras del RSS
echo '<rss version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd">';
//Datos generales del Canal. Edítalos conforme a tus necesidades
echo "<channel>\n";
echo "<title>Saldos de cuentas corrientes</title>";
echo "<link>http://contenidos.itris.com.ar/</link>";
echo "<description>Aquí mostramos la composición de saldos de Laboratorio de Bytes.</description>";
echo "<language>es-es</language>";
echo "<copyright>Leo Condorí ®</copyright>\n";

	
  while(odbc_fetch_row($rs_access))
{
	//elimino caracteres extraños en campos susceptibles de tenerlos
	//$titulo=clrAll($registro["TSKDESCRIPTION"]);			
	//$desc=clrAll($registro["TSKNOTES"]);

echo "<item>\n";
echo "<title>".odbc_result($rs_access,"RAZ_SOC") ."</title>\n";
echo "<description>".odbc_result($rs_access,"IMPORTE") ."</description>\n";
echo "<link>".odbc_result($rs_access,"ID") ."</link>\n";
echo "<pubDate>".odbc_result($rs_access,"FEC_MOV") ."</pubDate>\n";
echo "</item>\n";
		}

//cierro las etiquetas del XML
echo "</channel>";
echo "</rss>";

		//Liberamos el espacio de memoria ocupado por la consulta
		odbc_free_result ($rs_access);   
					}else
		{
		//echo "Error al ejecutar la sentencia SQL";
		}
} else{
		//echo "Error en la conexión con la base de datos";
		}
?>
