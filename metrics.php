<?php
$inicio = microtime(true);

// Simulamos procesamiento de 1 a 3 segundos
sleep(rand(1, 3));

// Métricas personalizadas
$conteo_subidas = 1;
$tiempo_procesamiento = microtime(true) - $inicio;

// Respuesta tipo Prometheus
header('Content-Type: text/plain');

echo "# HELP uploads_total Total de subidas\n";
echo "# TYPE uploads_total counter\n";
echo "uploads_total {$conteo_subidas}\n";

echo "# HELP upload_processing_seconds Tiempo de procesamiento\n";
echo "# TYPE upload_processing_seconds gauge\n";
echo "upload_processing_seconds {$tiempo_procesamiento}\n";
