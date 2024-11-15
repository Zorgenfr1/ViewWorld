USE ViewWorld;
CREATE TABLE users(
	userid VARCHAR(50) PRIMARY KEY,
    name VARCHAR(100),
    CONSTRAINT chk_userid CHECK (userid LIKE '@%'),
    profile_pic VARCHAR(255),
    bio TEXT,
    follower_count INT,
    following_count INT,
    post_count INT,
    email VARCHAR(255),
    password_hash VARCHAR(255)
);
	