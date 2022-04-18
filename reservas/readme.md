# Reservation area - Description

HTML , CSS , JAVASCRIPT , PHP , MYSQL

The system consists of:

- REGISTRATION and PAYMENT PAGE (accessible to the public): https://www.tamarafreitas.com/reservas/

  - An information page with information about the reservation, prices, recommendations, contraindications, etc.;

  - Form with the fields: NAME , SURNAME , DOCUMENT , EMAIL , PHONE , AVAILABILITY (2 checkboxes), BOOKING ITEM (3 checkboxes);

  - The system must receive the form information, receive payment through the payment platform API (STRIPE) and register the records occupying the available vacancies, under the following conditions:

    - Check that all form fields have been filled in correctly;
    - Check if there are vacancies available before registering and paying;
    - Prevent duplication, checking if the person has previously made a payment for the selected item (checking by document number);
    - Avoid "overbooking" of available spaces, taking into account that access is performed at the same time by thousands of people;
    - Information dialog windows about the registration status and payment confirmation;

    - It is not necessary to create a login;
    - The vacancies are separated by months (for example: April-15 vacancies May-12 vacancies June-7 vacancies);
    - Prices for reservations are the same, regardless of the item and type of customer (payment through the system);
    - The final prices for each item are different (but they will not be paid through the system, they will be paid in person at the time the service is performed);
    - Item prices are different for new customers and return customers;

- CONSULTATION PAGE (Accessible only by authorized persons)

  - Access can be through a single fixed password (no need to register users);
  - Listing of the reservation register with the information of the registers;
  - Research field;
  - Export list in EXCEL;

- THE SAME SYSTEM MUST SERVE FOR RESERVING NEW CUSTOMERS AND RETURN CUSTOMERS (IN SEPARATE REGISTRATIONS):

  - New customers are those who will make the purchase and service for the first time;
  - Returning customers are customers who have already performed the service, and are returning for an adjustment/repair.
  - The difference between new and returning customers will not be identified by the system. Vacancies will be opened at different times for each type of customer separately.

- ABOUT THE PRODUCT (both new and returning):

  - ITEMS:

        - Micropigmentation of eyebrows;
        - Micropigmentation of Lips;
        - Eye Micropigmentation (Eyeliner);

  - AVAILABILITY IN THE SCHEDULE:

        - Mornings;
        - Afternoons;

  - The vacancies are separated by months (for example: April-15 vacancies May-12 vacancies June-7 vacancies);
  - Prices for reservations are the same, regardless of the item and type of customer (payment through the system);
  - The final prices for each item are different (but they will not be paid through the system, they will be paid in person at the time the service is performed);
  - Item prices are different for new customers and return customers;
