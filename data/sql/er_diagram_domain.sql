CREATE DATABASE empalic_visit;
USE empalic_visit;

CREATE TABLE Patient (
            id INT PRIMARY KEY,
            citizenId VARCHAR(20) NOT NULL,
            firstName VARCHAR(100) NOT NULL,
            lastName VARCHAR(100) NOT NULL,
            phone VARCHAR(20) NOT NULL ,
            email VARCHAR(100),
            birthday DATE NOT NULL,
            gender CHAR(1) NOT NULL,
            province VARCHAR(100) NOT NULL,
            district VARCHAR(100) NOT NULL,
            ward VARCHAR(100) NOT NULL,
            street VARCHAR(100) NOT NULL,
            insurancePayer VARCHAR(100) NOT NULL,
            CONSTRAINT patient_citizen_id_unique UNIQUE (citizenId)
);

CREATE TABLE Synonym (
            id INT PRIMARY KEY,
            content VARCHAR(2000) NOT NULL,
            language VARCHAR(100) NOT NULL
);

CREATE TABLE Specialty (
            id INT PRIMARY KEY,
            code VARCHAR(200) NOT NULL,
            name VARCHAR(200) NOT NULL,
            synonymId INT NOT NULL
);

CREATE TABLE CareIntensity (
            id INT PRIMARY KEY,
            level SMALLINT NOT NULL,
            name VARCHAR(200) NOT NULL,
            description VARCHAR(2000) NOT NULL,
            nameSynonymId INT NOT NULL,
            descSynonymId INT NOT NULL
);

CREATE TABLE Service (
            id INT PRIMARY KEY,
            code VARCHAR(200) NOT NULL,
            name VARCHAR(200) NOT NULL,
            description VARCHAR(2000) NOT NULL,
            specialtyId INT NOT NULL,
            careIntensityId INT NOT NULL,
            nameSynonymId INT NOT NULL,
            descSynonymId INT NOT NULL,
            CONSTRAINT service_code_unique UNIQUE (code)
);

CREATE TABLE ClinicalNeedDetail (
            clinicalNeedId INT NOT NULL,
            serviceId INT NOT NULL,
            careIntensityId INT NOT NULL
);

CREATE TABLE ClinicalNeed (
            id INT PRIMARY KEY,
            specialityId INT NOT NULL,
            careIntensityId INT NOT NULL
);

CREATE TABLE Referral (
            id INT PRIMARY KEY,
            patientId INT NOT NULL,
            clinicalNeedId INT NOT NULL,
            fromProvider VARCHAR(200) NOT NULL,
            toProvider VARCHAR(200) NOT NULL,
            validDate DATE NOT NULL,
            expiredDate DATE NOT NULL
);

CREATE TABLE SlotScheduling (
            id VARCHAR(50) PRIMARY KEY,
            date DATE NOT NULL,
            room SMALLINT NOT NULL,
            providerName VARCHAR(200) NOT NULL
);

CREATE TABLE AnchorHour (
            id INT PRIMARY KEY,
            hour SMALLINT NOT NULL,
            totalSlots SMALLINT NOT NULL,
            schedulingId VARCHAR(50)
);

CREATE TABLE Slot (
            id VARCHAR(50) PRIMARY KEY,
            ordinal SMALLINT NOT NULL,
            hourSeq SMALLINT NOT NULL,
            anchorHourId INT NOT NULL
);

CREATE TABLE Appointment (
            id INT PRIMARY KEY,
            patientId INT NOT NULL,
            clinicalNeedId INT NOT NULL,
            slotId VARCHAR(50) NOT NULL
);

ALTER TABLE Specialty
ADD FOREIGN KEY (synonymId) REFERENCES Synonym(id);

ALTER TABLE CareIntensity
ADD FOREIGN KEY (nameSynonymId) REFERENCES Synonym(id),
ADD FOREIGN KEY (descSynonymId) REFERENCES Synonym(id);

ALTER TABLE Service
ADD FOREIGN KEY (specialtyId) REFERENCES Specialty(id),
ADD FOREIGN KEY (careIntensityId) REFERENCES CareIntensity(id),
ADD FOREIGN KEY (nameSynonymId) REFERENCES Synonym(id),
ADD FOREIGN KEY (descSynonymId) REFERENCES Synonym(id);

ALTER TABLE ClinicalNeedDetail
ADD FOREIGN KEY (clinicalNeedId) REFERENCES ClinicalNeed(id),
ADD FOREIGN KEY (serviceId) REFERENCES Service(id),
ADD FOREIGN KEY (careIntensityId) REFERENCES CareIntensity(id);

ALTER TABLE ClinicalNeed
ADD FOREIGN KEY (specialityId) REFERENCES Specialty(id),
ADD FOREIGN KEY (careIntensityId) REFERENCES CareIntensity(id);

ALTER TABLE Referral
ADD FOREIGN KEY (patientId) REFERENCES Patient(id),
ADD FOREIGN KEY (clinicalNeedId) REFERENCES ClinicalNeed(id);

ALTER TABLE AnchorHour
ADD FOREIGN KEY (schedulingId) REFERENCES SlotScheduling(id);

ALTER TABLE Slot
ADD FOREIGN KEY (anchorHourId) REFERENCES AnchorHour(id);

ALTER TABLE Appointment
ADD FOREIGN KEY (patientId) REFERENCES Patient(id),
ADD FOREIGN KEY (clinicalNeedId) REFERENCES ClinicalNeed(id),
ADD FOREIGN KEY (slotId) REFERENCES Slot(id);




