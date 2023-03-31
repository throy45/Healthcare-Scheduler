CREATE TABLE Facilities (
  FacilityID INT PRIMARY KEY,
  Name VARCHAR(255),
  Type VARCHAR(255),
  Capacity INT,
  WebAddress VARCHAR(255),
  PhoneNumber VARCHAR(255),
  Address VARCHAR(255),
  PostalCode VARCHAR(255),
  FOREIGN KEY (PostalCode) REFERENCES PostalCodes(PostalCode)
);

CREATE TABLE Employees (
  EmployeeID INT PRIMARY KEY,
  FName VARCHAR(255),
  LName VARCHAR(255),
  Role VARCHAR(255),
  DoBirth DATE,
  MedicareNumber VARCHAR(255) NOT NULL UNIQUE,
  Email VARCHAR(255),
  Citizenship VARCHAR(255),
  PhoneNumber VARCHAR(255),
  Address VARCHAR(255),
  PostalCode VARCHAR(255),
  FOREIGN KEY (PostalCode) REFERENCES PostalCodes(PostalCode)
);

CREATE TABLE Manager (
  EmployeeID INT PRIMARY KEY,
  FOREIGN KEY (EmployeeID) REFERENCES Employees(EmployeeID)
);

CREATE TABLE Vaccines (
  EmployeeID INT,
  FacilityID INT,
  VaccineID INT NOT NULL,
  Type VARCHAR(255),
  DoseNumber INT,
  Date DATE,
  PRIMARY KEY (EmployeeID, FacilityID, VaccineID),
  FOREIGN KEY (EmployeeID) REFERENCES Employees(EmployeeID),
  FOREIGN KEY (FacilityID) REFERENCES Facilities(FacilityID)
);

CREATE TABLE Infections (
  EmployeeID INT,
  InfectionID INT NOT NULL,
  Type VARCHAR(255),
  Date DATE,
  PRIMARY KEY (EmployeeID, InfectionID),
  FOREIGN KEY (EmployeeID) REFERENCES Employees(EmployeeID)
);

CREATE TABLE Employment (
  FacilityID INT,
  EmployeeID INT,
  ContractID INT,
  StartDate DATE NOT NULL,
  EndDate DATE,
  PRIMARY KEY (FacilityID, EmployeeID, ContractID),
  FOREIGN KEY (FacilityID) REFERENCES Facilities(FacilityID),
  FOREIGN KEY (EmployeeID) REFERENCES Employees(EmployeeID)
);

CREATE TABLE Managers (
  FacilityID INT,
  EmployeeID INT,
  StartDate DATE NOT NULL,
  EndDate DATE,
  PRIMARY KEY (FacilityID, EmployeeID, StartDate),
  FOREIGN KEY (FacilityID) REFERENCES Facilities(FacilityID),
  FOREIGN KEY (EmployeeID) REFERENCES Manager(EmployeeID)
);

CREATE TABLE EmailLog (
   FacilityID INT,
   EmployeeID INT,
   Date DATE,
   Subject VARCHAR(255),
   Body TEXT,
   PRIMARY KEY (FacilityID, EmployeeID, Date),
   FOREIGN KEY (FacilityID) REFERENCES Facilities(FacilityID),
   FOREIGN KEY (EmployeeID) REFERENCES Employees(EmployeeID)
);

CREATE TABLE Schedule (
   FacilityID INT,
   EmployeeID INT,
   Date DATE,
   StartTime TIME,
   EndTime TIME,
   PRIMARY KEY (FacilityID, EmployeeID, Date, StartTime),
   FOREIGN KEY (FacilityID) REFERENCES Facilities(FacilityID),
   FOREIGN KEY (EmployeeID) REFERENCES Employees(EmployeeID)
);

CREATE TABLE PostalCodes (
  PostalCode CHAR(7) PRIMARY KEY,
  City VARCHAR(255),
  Province VARCHAR(255)
);
