CREATE TABLE parking_vehicle (
                                id VARCHAR(255) NOT NULL,
                                id_vehicle VARCHAR(255) NOT NULL,
                                id_fleet VARCHAR(255) NOT NULL,
                                localization JSON NOT NULL,
                                PRIMARY KEY (id),
                                FOREIGN KEY (id_vehicle) REFERENCES vehicle(id) ON DELETE CASCADE,
                                FOREIGN KEY (id_fleet) REFERENCES fleet(id) ON DELETE CASCADE
);
