-- pos.urafiki.co.mz Database Schema

CREATE TABLE TblResellers (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    uid           VARCHAR(50)  NOT NULL UNIQUE,
    username      VARCHAR(100) NOT NULL UNIQUE,
    password      VARCHAR(255) NOT NULL,
    name          VARCHAR(150) NOT NULL,
    email         VARCHAR(150),
    isActive      TINYINT(1) DEFAULT 1,
    createdAt     DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE TblResellerIps (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    resellerId    INT NOT NULL,
    ipAddress     VARCHAR(45) NOT NULL,
    isActive      TINYINT(1) DEFAULT 1,
    createdAt     DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (resellerId) REFERENCES TblResellers(id)
);

CREATE TABLE TblAccounts (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    resellerId    INT NOT NULL UNIQUE,
    balance       DECIMAL(15,2) DEFAULT 0.00,
    creditLimit   DECIMAL(15,2) DEFAULT 0.00,
    updatedAt     DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (resellerId) REFERENCES TblResellers(id)
);

CREATE TABLE TblProviders (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    prefix        VARCHAR(10)  NOT NULL,
    operatorName  VARCHAR(100) NOT NULL,
    endpointUrl   VARCHAR(255),
    apiKey        VARCHAR(255),
    apiSecret     VARCHAR(255),
    rechargeType  ENUM('direct','pin') DEFAULT 'direct',
    params        JSON,
    isActive      TINYINT(1) DEFAULT 1,
    createdAt     DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE TblRateLimits (
    id               INT AUTO_INCREMENT PRIMARY KEY,
    resellerId       INT NOT NULL,
    ratePerSecond    INT DEFAULT 5,
    ratePerMinute    INT DEFAULT 100,
    ratePerDay       INT DEFAULT 5000,
    isActive         TINYINT(1) DEFAULT 1,
    FOREIGN KEY (resellerId) REFERENCES TblResellers(id)
);

CREATE TABLE TblSecurityRules (
    id                       INT AUTO_INCREMENT PRIMARY KEY,
    resellerId               INT,
    msisdnCooldownSeconds    INT DEFAULT 60,
    maxAmountPerDay          DECIMAL(15,2),
    maxTransactionsPerDay    INT,
    maxTransactionsPerMsisdn INT,
    isActive                 TINYINT(1) DEFAULT 1
);

CREATE TABLE TblBankDeposits (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    resellerId    INT NOT NULL,
    amount        DECIMAL(15,2) NOT NULL,
    reference     VARCHAR(150),
    confirmedBy   VARCHAR(100),
    createdAt     DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (resellerId) REFERENCES TblResellers(id)
);

CREATE TABLE TblMobile (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    msgId         VARCHAR(100) NOT NULL,
    transactionId VARCHAR(100) NOT NULL UNIQUE,
    uid           VARCHAR(50)  NOT NULL,
    msisdn        VARCHAR(20)  NOT NULL,
    prefix        VARCHAR(5),
    amount        DECIMAL(10,2) NOT NULL,
    debit         DECIMAL(10,2) NOT NULL,
    rechargeType  ENUM('direct','pin') DEFAULT 'direct',
    pin           VARCHAR(50),
    serial        VARCHAR(100),
    balanceAfter  DECIMAL(15,2),
    estado        TINYINT DEFAULT 0,
    fault         TINYINT DEFAULT 0,
    rawResponse   JSON,
    createdAt     DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE TblTv (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    msgId         VARCHAR(100) NOT NULL,
    transactionId VARCHAR(100) NOT NULL UNIQUE,
    uid           VARCHAR(50)  NOT NULL,
    decoderNumber VARCHAR(50)  NOT NULL,
    package       VARCHAR(100),
    amount        DECIMAL(10,2) NOT NULL,
    debit         DECIMAL(10,2) NOT NULL,
    pin           VARCHAR(50),
    serial        VARCHAR(100),
    balanceAfter  DECIMAL(15,2),
    estado        TINYINT DEFAULT 0,
    fault         TINYINT DEFAULT 0,
    rawResponse   JSON,
    createdAt     DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE TblElectricity (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    msgId         VARCHAR(100) NOT NULL,
    transactionId VARCHAR(100) NOT NULL UNIQUE,
    uid           VARCHAR(50)  NOT NULL,
    meterNumber   VARCHAR(50)  NOT NULL,
    amount        DECIMAL(10,2) NOT NULL,
    debit         DECIMAL(10,2) NOT NULL,
    token         VARCHAR(100),
    serial        VARCHAR(100),
    balanceAfter  DECIMAL(15,2),
    estado        TINYINT DEFAULT 0,
    fault         TINYINT DEFAULT 0,
    rawResponse   JSON,
    createdAt     DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE TblReports (
    id                  INT AUTO_INCREMENT PRIMARY KEY,
    uid                 VARCHAR(50)  NOT NULL,
    accountName         VARCHAR(150) NOT NULL,
    reportDate          DATE NOT NULL,
    filePath            VARCHAR(255),
    totalTransactions   INT DEFAULT 0,
    totalAmount         DECIMAL(15,2) DEFAULT 0.00,
    totalDebit          DECIMAL(15,2) DEFAULT 0.00,
    sentTo              VARCHAR(150),
    sentAt              DATETIME,
    createdAt           DATETIME DEFAULT CURRENT_TIMESTAMP
);
