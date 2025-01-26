CREATE TABLE vehicle (
                         id VARCHAR(255) NOT NULL,
                         license_plate VARCHAR(255) NOT NULL,
                         localization JSON NOT NULL,
                         PRIMARY KEY (id)
);
