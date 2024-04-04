### Para probar y validar usar Altair https://altairgraphql.dev/

# Uso de `customShippingMethods` consultas GraphQL

Para usar una consulta personalizada como `customShippingMethods`
usar la siguiente query para obtener los carriers activos

query {
customShippingMethods {
carrier_code
carrier_title
method_code
method_title
}
}


# Obtener lista de colonias `getColonyData` 
Basado en un atributo en Customer Address Attributes con codigo colonia de tipo select donde se pueden agregar desde admin los valores

query {
getColonyData {
id
name
}
}

# Actualizar la dirección del cliente `updateCustomerAddressCustomFields`
mutation {
updateCustomerAddressCustomFields(input: {
customer_address_id: 1,
municipio: "Tepojaco",
colonia: 229
}) {
success
}
}

