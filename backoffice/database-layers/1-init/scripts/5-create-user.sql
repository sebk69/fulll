CREATE TABLE user (
                      id VARCHAR(255) NOT NULL,
                      fleet_id VARCHAR(255) NOT NULL,
                      PRIMARY KEY (id),
                      FOREIGN KEY (fleet_id) REFERENCES fleet(id) ON DELETE CASCADE
);
