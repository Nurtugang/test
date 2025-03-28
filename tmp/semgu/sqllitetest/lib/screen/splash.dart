import 'package:flutter/foundation.dart';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert'; // Для работы с JSON

import '../../database/database_helper.dart';
import '../../screen/registration.dart';
import '../../screen/homeweb.dart';
import '../../globals/var.dart';

class SplashScreen extends StatefulWidget {
  @override
  _SplashScreenState createState() => _SplashScreenState();
}

class _SplashScreenState extends State<SplashScreen> {
  @override
  void initState() {
    super.initState();
    _checkUser();
  }

  Future<void> _checkUser() async {
    final users = await DatabaseHelper().getUser();

    WidgetsBinding.instance.addPostFrameCallback((_) async {
      if (users != null) {
        // Используйте context, который доступен в StatefulWidget
        GlobalUser.firstname = users['firstname'];
        GlobalUser.lastname = users['lastname'];
        GlobalUser.patronymic = users['patronymic'];
        GlobalUser.personid = users['personid'];
        GlobalUser.person = users['person'];
        GlobalUser.jwt = users['jwt'];
        var personid = GlobalUser.personid;
        var person = GlobalUser.person;
        var jwt = GlobalUser.jwt;
        ////// Запрос данных
        final url = Uri.parse(
            'https://apisdo.semgu.kz/apimobile/auth/persondata?person=$person&personid=$personid&jwt=$jwt');

        try {
          final response = await http
              .post(
                url,
                headers: {
                  'Content-Type': 'application/json',
                  'Accept': 'application/json',
                },
                body: jsonEncode({
                  'personid': GlobalUser.personid,
                  'person': GlobalUser.person
                }),
              )
              .timeout(Duration(seconds: 10));

          if (response.statusCode == 200) {
            final responseData = jsonDecode(response.body);

            if (responseData['success']) {
              GlobalUser.persondata = responseData;
            } else {
              // Ошибка авторизации
              setState(() {});
            }
          } else {
            setState(() {});
          }
        } catch (error) {
          if (kDebugMode) {
            print("🚨 Ошибка при отправке запроса: $error");
          }
          setState(() {});
        }
        ///////////
        Navigator.of(context).pushReplacement(
          MaterialPageRoute(builder: (context) => WebViewExample()),
        );
      } else {
        Navigator.of(context).pushReplacement(
          MaterialPageRoute(builder: (context) => RegistrationScreen()),
        );
      }
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Center(child: CircularProgressIndicator()),
    );
  }
}
