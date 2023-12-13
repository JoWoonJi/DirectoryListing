# 서버 내 취약점 공격 및 방어 과제

---

GitHub repository 

[https://github.com/JoWoonJi/DirectoryListing-SQL_Injection](https://github.com/JoWoonJi/DirectoryListing-SQL_Injection)

링크 [https://github.com/JoWoonJi/DirectoryListing-SQL_Injection](https://github.com/JoWoonJi/DirectoryListing-SQL_Injection)

---

아이디어 정리

- [x]  디렉터리 리스팅 취약점 테스트 및 방어
- [x]  SQL Injection 테스트 및 방어
- [x]  유재욱 멘토님 코멘트, pstmt에 대해 알아보기 `escaping보단 pstmt와같이 원천방어할 수 있게 하는게 중요`
- [ ]  preapared statement 적용해보기. 바드와 gpt를 활용해 보았지만 계속 에러가 나서 실패.

<aside>
💡 처음엔 SQL injection만 하려 했지만, 과제와 연결된 커리큘럼이 디렉터리 관련 취약점이라는 말을 듣고 추가로 시행하였다.

</aside>

---
---

    
# 1. Directory Listing

- URL에서 일정 부분을 지우고 접속하면 특정 디렉터리나 하위 디렉터리가 노출되게 되는데 이를 디렉터리 리스팅 공격이라고 한다. login_view.php 부분을 지우고 homepage 디렉터리에 접근이 되는지 테스트해 보자.

![1 디렉터리 리스팅 취약점 테스트](https://github.com/JoWoonJi/DirectoryListing-SQL_Injection/assets/153145539/8338bfea-b7fa-4a6d-a851-8730845a8dc9)
![대체 텍스트](./1.SQL Injection test admin 입력하고 로그인해 보기.jpg)
!(https://github.com/JoWoonJi/DirectoryListing-SQL_Injection/blob/main/img/1.SQL%20Injection%20test%20admin%20%EC%9E%85%EB%A0%A5%ED%95%98%EA%B3%A0%20%EB%A1%9C%EA%B7%B8%EC%9D%B8%ED%95%B4%20%EB%B3%B4%EA%B8%B0.jpg)

---

- 이처럼 디렉터리가 노출되게 되면 여러 문제가 발생하는데 공격자가 데이터를 그대로 탈취할 수도 있고 사진과 같이 db.php의 원본 소스를 보게 된다면 DB 루트 계정의 비밀번호가 그대로 노출 될 수도 있다.

![2 디렉터리 리스팅 취약점 확인](https://github.com/JoWoonJi/DirectoryListing-SQL_Injection/assets/153145539/05b8a072-308a-4dfd-98c4-e2ef93d75601)

---

- 이제 디렉터리 리스팅 공격을 방어해보자. 아파치 웹서버를 사용하고 있으므로 httpd.conf 파일에서 indexes 지시자를 찾아 주석처리 해주거나 삭제해준다.

![3 apache conf파일 수정하여 디렉터리 리스팅을 막아보자 ](https://github.com/JoWoonJi/DirectoryListing-SQL_Injection/assets/153145539/189125db-2bce-4cf5-9d66-106095c4fb1b)

---

- 디렉터리 리스팅 방어 성공// 똑같이 테스트 해 보면, 접근 권한이 없어 거부된 것이 보인다. indexes 지시자는 이렇게 하위 디렉터리 목록을 출력해주므로 삭제해주는 것이 좋고, FollowSymLinks 옵션만 삭제한다면 link 파일만 삭제되기 때문에 indexes지시자까지 삭제해줘야 한다.

![4 디렉터리에 접근되지 않고 거부](https://github.com/JoWoonJi/DirectoryListing-SQL_Injection/assets/153145539/128e6f05-a75c-41b5-a7ae-664681afb239)

---

---
---
---    

    
# 2. SQL Injection

- SQL injection은 가장 간단하지만 성공한다면 굉장히 큰 피해를 일으킨다. 그래서 웬만하면 보안코딩이 되어있지만, 사진처럼 허술한 페이지를 발견한다면 sql injection에 취약점이 있는지 테스트 해 보자. admin과 아무비밀번호를 입력하여 로그인 시도.

![1 SQL Injection test admin 입력하고 로그인해 보기](https://github.com/JoWoonJi/DirectoryListing-SQL_Injection/assets/153145539/a5f377a3-ce4f-47ce-b294-be1a33de5e14)

---

- bool이 뜨며 로그인이 되지 않는다.

![2 bool이 뜨며 로그인이 되지 않는다](https://github.com/JoWoonJi/DirectoryListing-SQL_Injection/assets/153145539/5386dec4-ecee-45ba-a5bb-5c32d56ad1eb)

---

- 이제 SQL Injection 공격에 취약점이 있는지 테스트해 보자. SQL Injection의 대표적인 구문인 ‘ or ‘1’=’1 구문. 1=1은 반드시 참이고 or 이기때문에 sql 쿼리문을 비밀번호에 상관없이 무조건 참으로 만들어 준다.
-  *SELECT * FROM MEMBER WHERE user_id='admin' or '1'='1'* 이런 sql 쿼리문 형태

![3 SQL injection의 대표적인 구문으로 관리자 계정을 탈취해보자](https://github.com/JoWoonJi/DirectoryListing-SQL_Injection/assets/153145539/ff1c26fc-50cd-4db1-83e1-81810a3e1fe6)

---

- bool이 뜨지 않고 SQL Injection 공격에 성공하여 admin(관리자)계정을 탈취하였다. 이처럼 간단한 공격이지만 관리자 계정을 탈취당할 위험이 있는 피해가 큰 공격이다.  사진처럼 adminpw!@#$ 비밀번호가 그대로 노출될 수도 있다.

![4 관리자 계정을 탈취하였다  쿼리문에 비밀번호가 그대로노출될 수도 있다](https://github.com/JoWoonJi/DirectoryListing-SQL_Injection/assets/153145539/b7effb48-2208-4b09-801f-5d9ce6beb1aa)

---

- 실제 DB 내 admin계정이 있고 비밀번호가 adminpw!@#$인 것을 확인 할 수 있다.

![5 DB 내 admin 계정 확인](https://github.com/JoWoonJi/DirectoryListing-SQL_Injection/assets/153145539/903a96ce-b1f5-4fb5-9586-c342859049be)

---

- id 와 pw를 바인딩하는 간단한 보안코딩을 적용해보자.

![6 id와 pw를 바인딩하여 간단한 보안코딩을 적용해보자](https://github.com/JoWoonJi/DirectoryListing-SQL_Injection/assets/153145539/7b8d9a26-d1cb-4fc7-8f05-bd14a875cac9)

---

- 보안코딩 적용 후 다시 SQL Injection을 시도해보면 bool 이 뜨며 로그인이 되지 않고, 방어에 성공한 것이 확인된다.

![7 보안코딩적용 후 다시 인젝션 시도](https://github.com/JoWoonJi/DirectoryListing-SQL_Injection/assets/153145539/a4412b6f-2108-4362-a9b9-36fe2a3da3f8)

![8 인젝션 방어 성공](https://github.com/JoWoonJi/DirectoryListing-SQL_Injection/assets/153145539/ddc7b9e0-41c4-4442-8c27-bd055d4b7a73)
