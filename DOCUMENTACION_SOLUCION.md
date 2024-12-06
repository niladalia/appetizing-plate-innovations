
### Solución planteada

Al contar con solo 3 horas para realizar el ejercicio, tenía varias opciones.
Mi idea inicial era desarrollar una aplicación completa que abarcara todos los aspectos (interfaz web, pruebas y API), aunque con una usabilidad mínima (1 o 2 endpoints).

Finalmente, decidí centrarme en una funcionalidad más compleja, implementando todos los endpoints requeridos (excepto el Delete, debido a la falta de tiempo y su baja complejidad). Esto me permitió ofrecer una visión más completa de mis conocimientos, al incluir casos de uso más avanzados, como la interacción entre Order e Items o la implementación de una "state machine".
Es cierto que, con este enfoque, limité la amplitud de la API y no tuve oportunidad de demostrar conocimientos en PHPUnit o en frontend. Sin embargo, tampoco habría tenido tiempo suficiente para desarrollar una suite de tests lo suficientemente sólida como para compensar el no haber completado la API..

He seguido la arquitectura Hexagonal, específicamente con DDD, empujando toda la lógica de dominio hacia la capa del Dominio. 
La estructura está organizada en tres capas distintas: Infraestructura, Aplicación y Dominio. En la capa de Infraestructura he colocado todas las dependencias externas y puntos de entrada; en la capa de Aplicación se encuentran los casos de uso; y en la capa de Dominio están los atributos, las entidades y la lógica del dominio.



### Si hubiera tenido más tiempo...
- Reorganizar la estructura de /src en src/frontend y src/api.
El directorio src/api contendría lo que actualmente se encuentra en /src, mientras que src/frontend alojaría las plantillas y los controladores necesarios para renderizar las distintas páginas.

- Añadir tests unitarios para cubrir los diferentes casos de uso, como ItemCreator, OrderFinder, etc.

- Implementar tests de aceptación utilizando Behat, con el objetivo de validar que la aplicación cumple los requisitos desde el punto de vista del cliente.

- Organizar la carpeta test siguiendo la misma estructura que el directorio /src. Los ValueObjects se podrían mockear utilizando Object Mothers.

- Incluir el endpoint para DELETE Item, con la validación (a nivel de dominio) de que no se pueda eliminar un Item si está asignado a un Order.

- Implementar autenticación de usuarios (camareros, administradores, etc.) mediante JWT. Esto garantizaría que, al añadir un Item o un Order, solo se devuelvan los objetos relacionados con ese usuario.
Para evitar incluir el ID del camarero en cada solicitud, se implementaría un middleware encargado de obtener el ID del camarero a partir del JWT y de inyectarlo en el controlador correspondiente.

- Tampoco tuve tiempo de revisar el Docker ni GitHub Actions.
