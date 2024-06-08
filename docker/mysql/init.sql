-- データベースの存在を確認して削除後、新規に作成
DROP DATABASE IF EXISTS citron;
CREATE DATABASE citron;
USE citron;

CREATE TABLE agency (
    id INT PRIMARY KEY AUTO_INCREMENT,
    agency_name VARCHAR(255) NOT NULL,
    strong_point VARCHAR(255) NOT NULL,
    image VARCHAR(255) NOT NULL,
    compatible_time VARCHAR(255) NOT NULL,
    address VARCHAR(255) NOT NULL,
    supplement VARCHAR(255),
    created_at DATE,
    updated_at DATE,
    approval TINYINT(1)
);

CREATE TABLE user (
    id INT PRIMARY KEY AUTO_INCREMENT,
    agency_id INT NOT NULL,
    user_id INT NOT NULL
);

CREATE TABLE user_info (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    sex TINYINT(1) NOT NULL,
    graduate_year INT NOT NULL,
    university VARCHAR(255) NOT NULL,
    selection TINYINT(1) NOT NULL,
    faculty VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(255) NOT NULL,
    supplement VARCHAR(255)
);

CREATE TABLE feature (
    id INT PRIMARY KEY AUTO_INCREMENT,
    agency_id INT NOT NULL,
    feature_id INT NOT NULL,
    status TINYINT(1)
);

CREATE TABLE feature_info (
    id INT PRIMARY KEY AUTO_INCREMENT,
    feature_name VARCHAR(255) NOT NULL,
    category_id INT NOT NULL
);

CREATE TABLE category (
    id INT PRIMARY KEY AUTO_INCREMENT,
    category_name VARCHAR(255) NOT NULL
);

CREATE TABLE levels (
    id INT PRIMARY KEY AUTO_INCREMENT,
    agency_id INT NOT NULL,
    levels_id INT NOT NULL,
    level INT NOT NULL
);

CREATE TABLE levels_info (
    id INT PRIMARY KEY AUTO_INCREMENT,
    levels_name VARCHAR(255) NOT NULL,
    levels_min VARCHAR(255) NOT NULL,
    levels_max VARCHAR(255) NOT NULL
);

CREATE TABLE editor (
    id INT PRIMARY KEY AUTO_INCREMENT,
    agency_id INT NOT NULL,
    editor_id INT NOT NULL
);

CREATE TABLE editor_info (
    id INT PRIMARY KEY AUTO_INCREMENT,
    editor_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE craft (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255),
    email VARCHAR(255),
    password VARCHAR(255)
);

INSERT INTO agency (agency_name, strong_point, image, compatible_time, address, supplement, created_at, updated_at, approval) VALUES
('マイナビ新卒紹介', 'みらいを、らしく、いこう。', 'マイナビ新卒紹介.jpg', '全日 9:15~18:00', '〒151-0051 東京都新宿区新宿４丁目１−６ JR新宿ミライナタワー 26階', 'キャリアアドバイザーと個別の面談（キャリアカウンセリング）を行い、一人ひとりの希望に合った求人を個別に紹介してもらえるのが特徴。 また、キャリアアドバイザーからプロの視点で書類の作成指導や面接対策をしてもらえるうえに、サービスはすべて無料で利用することが可能。', '2023-04-01', '2024-04-01', 1),
('レバテック', '企業があなたを待っている', 'レバテック.png', '平日 10:00~20:00  土日 10:00~16:00', '〒150-6190 東京都渋谷区渋谷２丁目２４−１２ 24F・25F', 'ITに特化した豊富な求人数と取引社数があるから高収入の求人・案件を多数ご提案できます。IT専門アドバイザーがあなたの経験と業界のトレンドから市場価値が高い強みを見抜きます', '2023-04-02', '2024-04-02', 1),
('キャリアチケット', 'さよなら、やみくも就活。', 'キャリアチケット.png', '平日 10:00~20:00', '〒150-0002 東京都渋谷区渋谷３丁目６−３ 363清水ビル 4F', '合う企業を見つけるため、まずはあなたにヒアリング。ヒアリングを元に、あなたに合う企業を紹介。紹介先は、実際に足を運んで調べた企業だから、社風や働き方、欲しい人材までしっかり把握済み。だから、ミスマッチのない、あなたを分かってくれる企業を紹介してもらえます。', '2023-04-03', '2024-04-03', 0),
('理系就職エージェントneo', '相談のできる就活を！', '理系就職エージェントneo.png', '平日9:30~18:30', '〒160-0023 東京都新宿区西新宿1-22-2 新宿サンエービル2階', '理系就職エージェントneoは、完全成果報酬型で理系学生に絞ってご紹介が可能なサービスです。機電・情報系を中心に、建築・土木学生の集客を強みとしています。 また紹介サービスと連動して、説明会や選考へ学生を直接送り込む送客サービス、オンラインでも開催しているイベントも合わせてご活用いただけます。', '2023-04-04', '2023-04-04', 1);

INSERT INTO category (category_name) VALUES
('得意企業'),
('業界'),
('主な地方'),
('その他');

INSERT INTO user (agency_id, user_id) VALUES
(1, 1),
(2, 1),
(3, 1),
(2, 2),
(3, 2),
(4, 3),
(1, 3);

INSERT INTO user_info (name, sex, graduate_year, university, selection, faculty, email, phone, supplement) VALUES
('船本雄月', 0, 2027, '明治大学', 1, '商', 'funamoto@gmail.com', '123-4567-8901', 'IT業界に興味があります'),
('荒谷英里', 1, 2027, '明治大学', 1, '商', 'aratani@gmail.com', '234-5678-9012', '商社に興味があります'),
('伊東柚衣', 1, 2027, '明治大学', 1, '情報コミュニケーション', 'itou@gmail.com', '345-6789-0123', '福祉に興味があります');

INSERT INTO feature (agency_id, feature_id, status) VALUES
(1, 1, 1),
(1, 2, 0),
(1, 3, 0),
(1, 4, 0),
(1, 5, 0),
(1, 6, 0),
(1, 7, 1),
(1, 8, 1),
(1, 9, 1),
(1, 10, 0),
-- (1, 11, 1),
(2, 1, 0),
(2, 2, 1),
(2, 3, 1),
(2, 4, 0),
(2, 5, 1),
(2, 6, 0),
(2, 7, 0),
(2, 8, 1),
(2, 9, 0),
(2, 10, 0),
-- (2, 11, 1),
(3, 1, 0),
(3, 2, 1),
(3, 3, 0),
(3, 4, 0),
(3, 5, 0),
(3, 6, 0),
(3, 7, 0),
(3, 8, 1),
(3, 9, 1),
(3, 10, 0),
-- (3, 11, 1),
(4, 1, 1),
(4, 2, 0),
(4, 3, 0),
(4, 4, 1),
(4, 5, 0),
(4, 6, 1),
(4, 7, 0),
(4, 8, 0),
(4, 9, 0),
(4, 10, 1);
-- (4, 10, 1),
-- (4, 11, 1);

INSERT INTO feature_info (feature_name, category_id) VALUES
('大企業', 1),
('ベンチャー', 1),
('IT特化', 2),
('理系特化', 2),
('首都圏', 3),
('地方', 3),
('実地面談', 4),
('非公開求人', 4),
('インターン紹介', 4),
-- ('逆スカウト', 4),
('逆スカウト', 4);
-- ('テスト', 2);



INSERT INTO levels (agency_id, levels_id, level) VALUES
(1, 1, 5),
(1, 2, 3),
(1, 3, 3),
-- (1, 4, 1),
(2, 1, 4),
(2, 2, 2),
(2, 3, 1),
-- (2, 4, 1),
(3, 1, 1),
(3, 2, 1),
(3, 3, 5),
-- (3, 4, 1),
(4, 1, 3),
(4, 2, 5),
-- (4, 3, 1),
(4, 3, 1);
-- (4, 4, 1);

INSERT INTO levels_info (levels_name, levels_min, levels_max) VALUES
('得意な企業規模', '中小', '大企業'),
('サポートのスピード感', 'ゆっくり', '素早く'),
('学生の独自コミュニティ', '個人', 'コミュニティあり');
-- ('学生の独自コミュニティ', '個人', 'コミュニティあり'),
-- ('テスト', '低い', '高い');

INSERT INTO editor (agency_id, editor_id) VALUES
(1, 1),
(1, 2),
(2, 3),
(2, 4),
(3, 5),
(3, 6),
(4, 7),
(4, 8);


insert into editor_info (editor_name, email, password ) VALUES
('山田太郎', 'admin1@example.com', '$2y$10$csAFREneXMq1sdnuvOrFWe.ZW0kDM3Qigy1S0bhFJ3hhc6fgpMEIy'),
('山田二郎', 'admin2@example.com', '$2y$10$csAFREneXMq1sdnuvOrFWe.ZW0kDM3Qigy1S0bhFJ3hhc6fgpMEIy'),
('山田三郎', 'admin3@example.com', '$2y$10$csAFREneXMq1sdnuvOrFWe.ZW0kDM3Qigy1S0bhFJ3hhc6fgpMEIy'),
('山田四郎', 'admin4@example.com', '$2y$10$csAFREneXMq1sdnuvOrFWe.ZW0kDM3Qigy1S0bhFJ3hhc6fgpMEIy'),
('山田五郎', 'admin5@example.com', '$2y$10$csAFREneXMq1sdnuvOrFWe.ZW0kDM3Qigy1S0bhFJ3hhc6fgpMEIy'),
('山田六郎', 'admin6@example.com', '$2y$10$csAFREneXMq1sdnuvOrFWe.ZW0kDM3Qigy1S0bhFJ3hhc6fgpMEIy'),
('山田七郎', 'admin7@example.com', '$2y$10$csAFREneXMq1sdnuvOrFWe.ZW0kDM3Qigy1S0bhFJ3hhc6fgpMEIy'),
('山田八郎', 'admin8@example.com', '$2y$10$csAFREneXMq1sdnuvOrFWe.ZW0kDM3Qigy1S0bhFJ3hhc6fgpMEIy');

insert into craft (name, email, password) VALUES
('田中', 'craft@example.com','$2y$10$csAFREneXMq1sdnuvOrFWe.ZW0kDM3Qigy1S0bhFJ3hhc6fgpMEIy');
