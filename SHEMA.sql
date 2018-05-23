/*DROP DATABASE parley_db;*/

CREATE DATABASE parley_db;

USE parley_db;

CREATE TABLE user(	user_id 	INT AUTO_INCREMENT PRIMARY KEY,
					username	VARCHAR(30),
                    firstname	VARCHAR(30),
                    lastname	VARCHAR(30),
                    password	VARCHAR(40),
                    gender		VARCHAR(1),
                    email		VARCHAR(30),
                    city		VARCHAR(30),
                    country		VARCHAR(30),
                    joindate	DATETIME,
                    picture		VARCHAR(30),
                    bio			VARCHAR(300)
					);
                
CREATE TABLE topic(	topic_id	INT AUTO_INCREMENT PRIMARY KEY,
					topic_name	VARCHAR(30)
				);

CREATE TABLE question(	question_id		INT AUTO_INCREMENT PRIMARY KEY,
						q_description 	VARCHAR(300),
                        views			INT,
                        user_id			INT	REFERENCES user(user_id),
						topic_id		INT	REFERENCES user(topic_id)
                        -- askdate      TIMESTAMP;
					);

CREATE TABLE answer(	answer_id		INT AUTO_INCREMENT PRIMARY KEY,
						a_description	VARCHAR(300),
                        views			INT,
						user_id			INT	REFERENCES user(user_id),
						question_id		INT	REFERENCES question(question_id)
					);
                    
CREATE TABLE comment(	comment_id		INT AUTO_INCREMENT PRIMARY KEY,
						c_description	VARCHAR(300),
						user_id			INT	REFERENCES user(user_id),
						answer_id		INT	REFERENCES answer(answer_id)
					);
                    
CREATE TABLE following_topic(	user_id			INT	REFERENCES user(user_id),
								topic_id		INT	REFERENCES user(topic_id)
							);
                            
CREATE TABLE following_question(	user_id			INT	REFERENCES user(user_id),
									question_id		INT	REFERENCES question(question_id)
								);
                                
CREATE TABLE voting_question(	user_id			INT	REFERENCES user(user_id),
								question_id		INT	REFERENCES question(question_id),
                                vote			INT DEFAULT 0
							);
		
CREATE TABLE voting_answer(		user_id			INT	REFERENCES user(user_id),
								answer_id		INT	REFERENCES answer(answer_id),
                                vote			INT DEFAULT 0
							);

CREATE TABLE voting_comment(	user_id			INT	REFERENCES user(user_id),
								comment_id		INT	REFERENCES comment(comment_id),
                                vote			INT DEFAULT 0
							);