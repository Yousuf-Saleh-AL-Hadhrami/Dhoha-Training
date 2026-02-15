

USE computer_parts_stock;

CREATE TABLE IF NOT EXISTS users(
          id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
          staff_name VARCHAR(200),
          username VARCHAR(100),
          password VARCHAR(64)
);

#=======================================================

CREATE TABLE IF NOT EXISTS device_types(
  id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  type_name VARCHAR(225) UNIQUE
);

#=========================================================

CREATE TABLE IF NOT EXISTS device_companies(
    id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    device_company_name VARCHAR(255) UNIQUE,
    device_type_id INT UNSIGNED,
    CONSTRAINT fk_device_type_id FOREIGN KEY (device_type_id) REFERENCES device_types(id)
);

#=========================================================

CREATE TABLE IF NOT EXISTS device_models(
    id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    device_model VARCHAR(255) UNIQUE,
    device_type_id INT UNSIGNED,
    device_company_id INT UNSIGNED,
    CONSTRAINT fk_device_type_id2 FOREIGN KEY (device_type_id) REFERENCES device_types(id),
    CONSTRAINT fk_device_company_id FOREIGN KEY (device_company_id) REFERENCES device_companies(id)
);

#=========================================================
CREATE TABLE stores (
    id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    store_name VARCHAR(100) NOT NULL
);

#===================================================================

CREATE TABLE IF NOT EXISTS device_stock(
    id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    device_status enum('جديد','مستعمل') default 'مستعمل',
    expensed_status enum('مصروف','غير مصروف') default 'غير مصروف',
    device_type_id INT UNSIGNED,
    device_company_id INT UNSIGNED,
    device_model_id INT UNSIGNED,
    store_id INT UNSIGNED,

    created_at timestamp default current_timestamp,
    deleted_at timestamp default current_timestamp,
    expensed_at timestamp  null default null,

    CONSTRAINT fk_device_type_id3 FOREIGN KEY (device_type_id) REFERENCES device_types(id),
    CONSTRAINT fk_device_company_id2 FOREIGN KEY (device_company_id) REFERENCES device_companies(id),
    CONSTRAINT fk_device_model_id FOREIGN KEY (device_model_id) REFERENCES device_models(id),
    CONSTRAINT fk_store_id FOREIGN KEY (store_id) REFERENCES stores(id)
);



#=========================================================

CREATE TABLE IF NOT EXISTS expenses (
    id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    
    serial_number VARCHAR(100)  NOT NULL,
    user_id INT UNSIGNED,
    device_type_id INT UNSIGNED,
    device_company_id INT UNSIGNED,
    device_model_id INT UNSIGNED,
    device_stock_id INT UNSIGNED,
    store_id INT UNSIGNED,
    

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL DEFAULT NULL,
    expensed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_user_id FOREIGN KEY (user_id) REFERENCES users(id),
    CONSTRAINT fk_device_type_id4 FOREIGN KEY (device_type_id) REFERENCES device_types(id),
    CONSTRAINT fk_device_company_id3 FOREIGN KEY (device_company_id) REFERENCES device_companies(id),
    CONSTRAINT fk_device_model_id1 FOREIGN KEY (device_model_id) REFERENCES device_models(id),
    CONSTRAINT fk_device_stock_id FOREIGN KEY (device_stock_id) REFERENCES device_stock(id),
    CONSTRAINT fk_store_id1 FOREIGN KEY (store_id) REFERENCES stores(id)
);


#=========================================================




