# Appetizing Plate Innovations Inc.

A PHP coding exercised based on a fake technology company revolutionising restaurants around nowhere.

Tarea: un sistema de pedidos de restaurante

Resumen: El candidato deberá diseñar e implementar una pequeña parte de un sistema de pedidos de restaurante utilizando Docker, PHP, PHPUnit, OpenAPI y REST. Son libres de priorizar las áreas con las que se sientan más cómodos, aunque se agradecerá una presentación diversa.

Para levantar este proyecto, revisa las instrucciones mas abajo, o revisa el Makefile.

## Instrucciones del ejercicio

1. Utilice Docker para el entorno de la aplicación.
2. Utilice PHPUnit para realizar pruebas, siendo deseable que se implemente al menos 1 caso de uso para cada funcionalidad implementada.
3. Asegúrese de que su código cumpla con las mejores prácticas de codificación, piense en la arquitectura y la mantenibilidad.
4. El uso de OpenAPI, REST, etc nos parece interesante. En el ejemplo provisto, exponemos la especificación en [http://localhost/api/docs](http://localhost/api/docs) con Swagger.
5. Los controladores, modelos, DTO, etc.. proporcionados en `src/Api` así como los tests, se trata de un ejemplo para ilustrar una estructura mínima, en el entregable final se deben eliminar estos ejemplos.
6. No es obligatorio ni necesario respetar la estructura propuesta en el ejemplo, siéntase libre de modificarla si así lo considera.
7. Opcionalmente, pueden integrar CICD utilizando GitLab, GitHub Actions, o cualquier sistema similar para automatizar el lint de código, tests unitarios y/o el build de la imagen final lista para ser desplegada con Docker.
8. No es necesario implementar autenticación ni securizar la API, el directorio `Auth` está como apoyo para ayudar a esta implementación si se opta por añadirla, pero es suficiente con el desarrollo de la funcionalidad requerida.

## Escenario: Sistema de pedidos

Se debe implementar una API con los endpoints necesarios para cumplir con los requisitos del escenario. La organización de estos endpoints, estructura, métodos, parámetros, así como modelos e interfaces a implementar queda a decisión libre del candidato, mientras se cumpla la funcionalidad requerida.

El escenario en el que trabajará es el siguiente:

1. Un restaurante necesita un sistema de pedidos donde pueda agregar o eliminar artículos (cada artículo tendrá nombre, precio y descripción).
2. Los camareros deben poder crear un pedido, al que podrán añadirle una serie de artículos y el número de unidades de cada uno.
3. Los pedidos pueden encontrarse en 3 estados (recibido -> en preparación -> listo para servir), y que se pueda cambiar el estado cuando sea requerido respetando el flujo detallado a continuación:
  - Cuando se crea un nuevo pedido se encontrará en estado "recibido", y solo podrá pasar a estado "en preparación".
  - Cuando un pedido se encuentra "en preparación", solo podrá pasar a estado "listo para servir".
  - Cuando un pedido se encuentra "listo para servir", ya no podrá cambiar a otro estado.
4. El sistema debería calcular el monto total del pedido.
5. Debe haber un endpoint donde el camarero pueda consultar el estado actual de su pedido (recibido, en preparación, listo para servir).
6. No es necesario securizar la API, pero si decide hacerlo, basta con el uso de API Tokens.

Por favor tenga en cuenta:

1. El objetivo principal de este ejercicio es evaluar su conocimiento y experiencia práctica en Docker, PHP, PHPUnit, OpenAPI, REST. Opcionalmente, puede mostrar sus conocimientos en CI/CD usando gitlab o github.
2. Después de finalizar el ejercicio, comparta el código fuente a través de un repositorio privado de github/gitlab y proporcione un archivo README que contenga los pasos para ejecutar la aplicación, y los entregables descritos a continuación.

## Entregables:

1. Código fuente en un fork o copia de este repositorio
2. Una breve documentación (en forma de archivo Markdown) que describa la solución, además de las elecciones/suposiciones realizadas.
3. Instrucciones de ejecución (se pueden incluir en README).
4. Cualquier caso de prueba que haya creado.

## Instrucciones del repositorio

Para levantar el proyecto la primera vez,

```bash
make init up
```

y de ahi en adelante, puedes 'reiniciar' con 

```bash
make down 
# y
make up
```

Puedes editar manualmente el `.env.dev` generado automaticamente al levantar el proyecto por primera vez.
