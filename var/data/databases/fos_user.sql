CREATE TABLE fos_user (id INTEGER NOT NULL, username VARCHAR(255) NOT NULL, username_canonical VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, email_canonical VARCHAR(255) NOT NULL, enabled BOOLEAN NOT NULL, salt VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, locked BOOLEAN DEFAULT 0, expired BOOLEAN DEFAULT 0, expires_at DATETIME DEFAULT NULL, confirmation_token VARCHAR(255) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles TEXT NOT NULL, credentials_expired BOOLEAN DEFAULT 0, credentials_expire_at DATETIME DEFAULT NULL);
INSERT INTO fos_user VALUES (1,'admin','admin','admin@domain.fr','admin@domain.fr','1','l4jka1hl5dcssw8co4c0o88wo0ocsgc','chclOX478nU1itXHmvWEPz6DY95+pQG+JotCk7Gpz0M4oApeewuASgkt3FPUKOQ62+mCbR+8P+jFUMTmx3C9yw==','2016-08-05 18:18:42','0','0',NULL,NULL,NULL,'a:1:{i:0;s:16:"ROLE_SUPER_ADMIN";}','0',NULL);
INSERT INTO fos_user VALUES (2,'guest','guest','guest@domain.fr','guest@domain.fr','1','jipy7a8g63kkkos0gccokoosc8g48oc','LJhnFkH4a+ZfuIo9fBXM+WdVY7y3yjks4XhI4CZedanUWpxi0xbEcyvEI1P1EnS0IB3bo1RX4J5u1LoIllCtAw==','2016-03-10 22:50:21','0','0',NULL,NULL,NULL,'a:0:{}','0',NULL);
CREATE UNIQUE INDEX UNIQ_957A6479A0D96FBF ON fos_user (email_canonical);
CREATE UNIQUE INDEX UNIQ_957A647992FC23A8 ON fos_user (username_canonical);
