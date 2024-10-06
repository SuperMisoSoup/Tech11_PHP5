# Tech11_PHP5

# ①課題番号-プロダクト名

VideoCentral(AmazonPrimeビデオのCMS)用Availsシート管理DB ログイン機能付き
- 業務で使う用
- AmazonPrimeビデオで提供する作品のライセンス情報を管理するDB
- 作品ごとに登録された最新のライセンスを参照できる
- ↑にログイン機能を追加し、ユーザ種別ごとに機能を制限
- 　管理者：データ登録・閲覧が可能、ユーザ追加・退職が可能
- 　運用者：データ登録・閲覧が可能
- 　閲覧者：データ閲覧のみ可能

## ③DEMO

https://docomo-tech-tkn.sakura.ne.jp/11_PHP5/login.php

## ④作ったアプリケーション用のIDまたはPasswordがある場合

- ID：test1
- PW：test1
- 
- 入力サンプルファイルで動作確認可能です
  - ※キー：AQ列のALID
  - 新規登録：DBにALIDが存在しない & G列EntryType＝Full Extract
  - 更新　　：DBにALIDが存在する & G列EntryType＝Full Extract
  - 削除　　：DBにALIDが存在する & G列EntryType＝Full Delete
  
## ⑤工夫した点・こだわった点

- ユーザ種別により機能を制限させたところ
- 「機能を制限する」というより「機能を担うphpにアクセスするボタンやリンクを表示させない」で実装させることを学んだ
- 基本的にはphpのif文で囲むだけなので、理解できれば簡単に実装できた

## ⑥難しかった点・次回トライしたいこと(又は機能)

- CSSで画面にこだわる暇がなかった
- フレームワークなどを使って早く簡単にいい感じの見た目を実現する方法を学びたい
