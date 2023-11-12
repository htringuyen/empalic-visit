/* Step 1: create TABLE named care_intensity_csv that hold values in care_intensity.csv */
USE empalic_visit;

CREATE TABLE care_intensity_csv (
            id INT PRIMARY KEY,
            level SMALLINT NOT NULL,
            name VARCHAR(200) NOT NULL,
            description VARCHAR(2000) NOT NULL,
            name_vn VARCHAR(2000) NOT NULL,
            description_vn VARCHAR(2000) NOT NULL
);

# /*Step 2: load data to above TABLE using LOAD DATA INFILE*/
# LOAD DATA INFILE '/Users/ngoctram/empalic-visit/data/csv/care_intensity.csv'
# INTO TABLE care_intensity_csv
# FIELDS TERMINATED BY ','
# ENCLOSED BY '"'
# LINES TERMINATED BY '\n'
# IGNORE 1 ROWS;

#/*Step 3:  */








