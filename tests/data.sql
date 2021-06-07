INSERT INTO user (user_id, last_name, first_name, user_name, user_email, password, activation, created_on, last_modified_on)
VALUES
  (NULL, 'John', 'Doe', 'johndoe', 'johndoe@gmail.com', '$2y$10$N6TBjabtWrYsTFAMnJyCVuVv.uO6cI074B8ok5W.S7uZ5FdoC1hPW', 'activated' , NOW(), NOW()),
  (NULL, 'Jeff', 'Doe', 'jeffsmith', 'jeffsmith@gmail.com', '$2y$10$N6TBjabtWrYsTFAMnJyCVuVv.uO6cI074B8ok5W.S7uZ5FdoC1hPW', 'activated' , NOW(), NOW());

INSERT INTO post (title, body, author_id, created_on, last_modified_on)
VALUES
  ('Test title', 'Test body', 1, '1564665294', NOW()),
  ('Remember', 'Hay un lugar más allá del tiempo, dónde existe un mundo mejor. Hay un lugar allá en lo eterno, dónde te sonríen la luna y el sol. A ese lugar yo te llevaré, yo te guiaré, al fin lo encontraré. Y a ese lugar yo te llevaré.', 1, '1564665776', NOW()),
  ('Reflexión sobre el oro', 'El valor del oro es un cáncer cultural tan incrustado dentro de nosotros como el metal lo está dentro de la roca, la próxima vez que tú le regales a él o a ella un anillo de oro como prueba de tu amor, debes saber que por cada anillo de oro nuevo que se crea, se mueven 250 toneladas de roca y medio kilo de mercurio se vierte al medio ambiente afectando gravemente a humanos animales y plantas. Seguro que hay mejores maneras de expresar amor por alguien que regalando oro.', 1, '1564665294', NOW());