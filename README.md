# Nortdaniel

## Instalación y Configuración

Instrucciones paso a paso para instalar el proyecto y configurarlo correctamente. Esto puede incluir comandos para ejecutar en la terminal, configuraciones de entornos, y cualquier dependencia necesaria.

```bash
php bin/magento setup:upgrade
```


### Características Principales
#### Checkout Fields
Descripción de cómo manejar y generar de forma segura atributos sobre la dirección de los clientes, incluyendo municipio (texto) y colonia (selección).

### Uso de customShippingMethods y GraphQL
Ejemplo de query para obtener los métodos de envío personalizados y cómo obtener una lista de colonias usando getColonyData.


```graphql
query {
  customShippingMethods {
    carrier_code
    carrier_title
    method_code
    method_title
  }
}

query {
  getColonyData {
    id
    name
  }
}
```
### Actualizar la dirección del cliente
Cómo usar la mutación updateCustomerAddressCustomFields para actualizar los campos personalizados de la dirección del cliente.

```graphql
mutation {
  updateCustomerAddressCustomFields(input: {
    customer_address_id: 1,
    municipio: "Tepotzotlan",
    colonia: 229 {codigo númerico obtenido en getColonyData}
  }) {
    success
  }
}

```

### OverPrice y PostPurchase
Información sobre cómo modificar los precios de los productos añadiendo un sobreprecio y cómo generar y obtener datos de una orden post-compra.
#### Uso de la API de Post-Compra

Esta sección del `README.md` detalla cómo interactuar con la API de post-compra para obtener los logs de las órdenes. Este proceso permite a los usuarios ver información detallada sobre las órdenes realizadas, facilitando un seguimiento efectivo y la gestión de las mismas.

#### Proceso para Obtener los Logs de Órdenes

Para utilizar la API y obtener los logs de las órdenes, sigue los pasos descritos a continuación:

#### Paso 1: Generar una Orden

Realiza el proceso estándar de tu tienda para crear una nueva orden de compra. Esto puede ser a través de la interfaz de usuario de la tienda o mediante una llamada API específica para la creación de órdenes.

#### Paso 2: Extraer el `entity_id`

Una vez creada la orden, identifica y extrae el `entity_id` de la misma. Este ID es único para cada orden y será necesario para realizar consultas específicas a través de la API.

#### Paso 3: Utilizar la API para Obtener los Datos

Con el `entity_id` en mano, utiliza la siguiente URL para hacer una solicitud GET a la API y obtener los datos de la orden:

```plaintext
{base_url}/V1/order-log/:order_id
```


### Redirección
Instrucciones para usar la funcionalidad de redirección y mostrar mensajes a través de la URL especificada.
```plaintext
{base_url}/nortdaniel/message/

```


### Nuevo Método de Envío y Configuración desde el Admin
Detalles sobre cómo se configura el método de envío desde el panel de administración de Magento y cómo estas configuraciones afectan el proceso de checkout.

### Parte I - Magento II
Descripción de las tareas específicas a realizar dentro de Magento, incluyendo la creación de módulos, configuración de la tienda, y la extensión de módulos del core.

### Parte II - Integración de APIs
API REST
Detalles sobre la creación del Observer Post-Compra, cómo definir el endpoint de la API y la implementación de seguridad para el acceso a la API.

### GraphQL
Cómo extender GraphQL para interactuar con servicios, incluyendo ejemplos de queries y mutaciones personalizadas y la implementación de resolvers.



**nortdaniel/Nortdaniel** is a ✨ _special_ ✨ repository because its `README.md` (this file) appears on your GitHub profile.

Here are some ideas to get you started:

- 🔭 I’m currently working on ...
- 🌱 I’m currently learning ...
- 👯 I’m looking to collaborate on ...
- 🤔 I’m looking for help with ...
- 💬 Ask me about ...
- 📫 How to reach me: ...
- 😄 Pronouns: ...
- ⚡ Fun fact: ...

