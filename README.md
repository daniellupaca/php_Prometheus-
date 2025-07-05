# 🛰️ Observabilidad en PHP con Prometheus
La **observabilidad** es una propiedad clave de los sistemas modernos que permite comprender lo que ocurre dentro de una aplicación mediante la recopilación de señales como métricas, logs y trazas. A diferencia del simple monitoreo (que solo detecta síntomas), la observabilidad nos permite diagnosticar las causas raíz de los problemas.

En entornos distribuidos, contar con herramientas de observabilidad es esencial para detectar cuellos de botella, caídas de rendimiento, errores ocultos y comportamientos inesperados. Una de las soluciones más populares y accesibles es **Prometheus**, un recolector de métricas de código abierto creado originalmente por SoundCloud, que permite consultar datos en tiempo real mediante su propio lenguaje de consultas: **PromQL**.

En este artículo te muestro cómo implementar un flujo completo de observabilidad en **PHP**, utilizando únicamente Prometheus.

---

## 🎯 ¿Qué aprenderás?

- Qué es la observabilidad y por qué importa
- Cómo generar métricas personalizadas con PHP
- Cómo configurar Prometheus para recolectarlas
- Cómo visualizar las métricas directamente en el dashboard de Prometheus

---

## 🧰 Herramientas utilizadas

| Herramienta     | Propósito                        |
|-----------------|----------------------------------|
| **PHP (CLI)**   | Simulación y exposición de métricas |
| **Prometheus**  | Recolección y consulta de métricas |
| **Windows**     | Entorno local de desarrollo       |

---

## 🧪 Paso 1: Generar métricas desde PHP

Creamos dos archivos PHP: uno que genera las métricas simuladas y otro que las expone para ser recolectadas por Prometheus.

### 📄 `generador.php`

```php
<?php
$conteo = 0;

while (true) {
    $inicio = microtime(true);
    sleep(rand(1, 3));

    $conteo++;
    $tiempo = microtime(true) - $inicio;

    file_put_contents("metricas.txt", 
        "# HELP uploads_total Total de subidas\n" .
        "# TYPE uploads_total counter\n" .
        "uploads_total {$conteo}\n" .
        "# HELP upload_processing_seconds Tiempo de procesamiento\n" .
        "# TYPE upload_processing_seconds gauge\n" .
        "upload_processing_seconds {$tiempo}\n"
    );

    sleep(1);
}
```

### 📄 `metrics.php`

```php
<?php
header('Content-Type: text/plain');
readfile("metricas.txt");
```

---

## ▶️ Paso 2: Ejecutar los scripts

1. Abre una terminal y ejecuta el generador:
```bash
php generador.php
```

2. En otra terminal, ejecuta el servidor PHP:
```bash
php -S localhost:8000 metrics.php
```

3. Verifica que todo funcione abriendo:
👉 http://localhost:8000

---

## ⚙️ Paso 3: Configurar Prometheus

1. Descarga Prometheus desde: https://prometheus.io/download
2. Extrae el ZIP y coloca este archivo como `prometheus.yml`:

```yaml
global:
  scrape_interval: 5s

scrape_configs:
  - job_name: 'php_metrics'
    static_configs:
      - targets: ['localhost:8000']

  - job_name: 'prometheus'
    static_configs:
      - targets: ['localhost:9090']
```

---

## ▶️ Paso 4: Ejecutar Prometheus

Desde PowerShell o CMD, ejecuta:

```bash
.\prometheus.exe --config.file=prometheus.yml
```

Abre: 👉 http://localhost:9090

---

## 📈 Paso 5: Consultar métricas en Prometheus

1. En el panel, ve a “Status → Targets” y asegúrate que esté **UP**
2. En la pestaña “Graph”, escribe:

```
uploads_total
```

y luego:

```
upload_processing_seconds
```

Presiona Execute y verás las métricas generadas por PHP.

---

![Image description](https://dev-to-uploads.s3.amazonaws.com/uploads/articles/2xl5caj8cz8r38uo2z0s.png)


## 📊 1. `upload_processing_seconds`

```
upload_processing_seconds{instance="localhost:8000", job="php_metrics"} 3.0004351139069
```

**Descripción:**
Esta métrica representa el **tiempo que tomó procesar una subida de archivo** en la aplicación PHP. En este caso, tomó aproximadamente **3 segundos**.

**Etiquetas asociadas:**
- `instance="localhost:8000"`: Indica que la métrica proviene del endpoint que corre en el puerto 8000 (donde está expuesto `metrics.php`).
- `job="php_metrics"`: Nombre del trabajo configurado en `prometheus.yml`.

---

## 📦 2. `uploads_total`

```
uploads_total{instance="localhost:8000", job="php_metrics"} 1
```

**Descripción:**
Esta métrica es un contador de **cuántas veces se ha ejecutado el proceso de subida**. En este ejemplo, solo se ha registrado una subida.

**Etiquetas asociadas:**
- `instance="localhost:8000"`: Fuente del endpoint de métricas.
- `job="php_metrics"`: Trabajo registrado para esa métrica.

---



## 📦 Repositorio de código

https://github.com/tuusuario/observabilidad-php-prometheus *(reemplaza con tu enlace real)*

---

## 🧠 Conclusión

Prometheus permite instrumentar cualquier aplicación moderna sin mucha complejidad. Al integrar métricas en tiempo real desde PHP, conseguimos una solución accesible para proyectos académicos, pruebas de rendimiento o sistemas reales sin necesidad de herramientas externas como Grafana.

Este enfoque puede extenderse fácilmente a Node.js, Python, Java, etc.

---

## ❤️ ¿Te fue útil?

Si te gustó este artículo, compártelo o comenta tu experiencia con Prometheus y PHP.
