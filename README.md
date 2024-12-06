
<h1 align="center">
  Appetizing Plate Innovations Inc.
</h1>

## Installation and configuration

### Clone repository

1. Clone this project into a machine with
   Docker installed

       git clone https://github.com/niladalia/appetizing-plate-innovations
2. Copy the .env to .env.dev

       cp .env .env.dev
2. Move to the project folder:

        cd appetizing-plate-innovations

### Project setup

1. Install all dependencies :

        make init up


2. Now you can access the API documentation http://localhost/api/docs

### Play with it

1. Start by creating an Item /api/item POST
2. Now create an Order /api/order POST
3. At this point you can add an Item to an Order, while also specifying the quantity and the item ID in the body /api/order/{orderId}/item POST
4. Check the status of the order as well as the total price of the order in GET /api/order/{id}. The total price is the result of multiplying the quantity by the price for all items related to the order.
5. You can change the status of the order using the /api/order/{id}/status PATCH. Note that the status transitions must adhere to specific rules and flow constraints!
