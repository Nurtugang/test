import 'package:flutter/material.dart';
import 'dart:convert'; // Для работы с JSON
import 'package:http/http.dart' as http;
//import 'package:restart_app/restart_app.dart';

import '../../database/database_helper.dart';
import '../../globals/var.dart';
import '../../screen/splash.dart';

class RegistrationScreen extends StatefulWidget {
  @override
  State<RegistrationScreen> createState() => _RegistrationScreenState();
}

class _RegistrationScreenState extends State<RegistrationScreen> {
  final TextEditingController loginController = TextEditingController();
  final TextEditingController passwordController = TextEditingController();
  ///////
  Future<void> _authorize(String login, String password) async {
    final url = Uri.parse(
        'https://apisdo.semgu.kz/apimobile/auth/login'); // Замените на ваш API URL

    try {
      final response = await http
          .post(
            url,
            headers: {'Content-Type': 'application/json'},
            body: jsonEncode({'login': login, 'password': password}),
          )
          .timeout(Duration(seconds: 10));

      if (response.statusCode == 200) {
        final responseData = jsonDecode(response.body);

        if (responseData['success']) {
          // Успешная авторизация: получите данные пользователя
          //final userData = responseData['data'];
          GlobalUser.firstname = responseData['firstname'];
          GlobalUser.lastname = responseData['lastname'];
          GlobalUser.patronymic = responseData['patronymic'];
          GlobalUser.personid = responseData['personid'];
          GlobalUser.person = responseData['person'];
          GlobalUser.jwt = responseData['jwt'];
          GlobalUser.photo = responseData['photo'];
          await DatabaseHelper().insertUser({
            'id': responseData['personid'],
            'firstname': responseData['firstname'],
            'lastname': responseData['lastname'],
            'patronymic': responseData['patronymic'],
            'personid': responseData['personid'],
            'person': responseData['person'],
            'jwt': responseData['jwt']
          });
          Navigator.of(context).pushReplacement(
            MaterialPageRoute(builder: (context) => SplashScreen()),
          );
        } else {
          // Ошибка авторизации
          setState(() {});
        }
      } else {
        setState(() {});
      }
    } catch (error) {
      setState(() {});
    }
  }
///////

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text('Регистрация')),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          children: [
            TextField(
              controller: loginController,
              decoration: InputDecoration(labelText: 'Логин'),
            ),
            TextField(
              controller: passwordController,
              decoration: InputDecoration(labelText: 'Пароль'),
              //keyboardType: TextInputType.number,
            ),
            SizedBox(height: 20),
            ElevatedButton(
              onPressed: () async {
                _authorize(loginController.text, passwordController.text);

                Navigator.pushReplacement(
                  context,
                  MaterialPageRoute(builder: (context) => RegistrationScreen()),
                  //MaterialPageRoute(builder: (context) => MainScreen()),
                );
              },
              child: Text('Зарегистрироваться'),
            ),
          ],
        ),
      ),
    );
  }
}
