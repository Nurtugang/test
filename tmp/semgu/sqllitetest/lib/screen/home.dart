import 'package:flutter/material.dart';

import '../../globals/var.dart';

class UserProfileScreen extends StatefulWidget {
  // Данные пользователя (можно передать их через конструктор)

  const UserProfileScreen({super.key});

  @override
  State<UserProfileScreen> createState() => _UserProfileScreenState();
}

class _UserProfileScreenState extends State<UserProfileScreen> {
  @override
  Widget build(BuildContext context) {
    var lastname = GlobalUser.lastname;
    var mail = GlobalUser.persondata['mail'];
    var urlphoto = GlobalUser.persondata['photo'];
    return Scaffold(
      appBar: AppBar(
        title: Text('Профиль'),
        centerTitle: true,
      ),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.center,
          children: [
            // Аватар пользователя
            CircleAvatar(
              radius: 60,
              backgroundImage: NetworkImage(urlphoto!),
            ),
            const SizedBox(height: 16),
            // Имя пользователя
            Text(
              lastname!,
              style: TextStyle(
                fontSize: 24,
                fontWeight: FontWeight.bold,
              ),
            ),
            const SizedBox(height: 8),
            // Электронная почта пользователя
            Text(
              mail!,
              style: TextStyle(
                fontSize: 16,
                color: Colors.grey[700],
              ),
            ),
            const SizedBox(height: 32),
            // Кнопка выхода из аккаунта
            ElevatedButton.icon(
              onPressed: () {
                _logout(context);
              },
              icon: Icon(Icons.logout),
              label: Text('Выйти'),
              style: ElevatedButton.styleFrom(
                backgroundColor: Colors.red,
                foregroundColor: Colors.white,
                padding:
                    const EdgeInsets.symmetric(horizontal: 24, vertical: 12),
                shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(8),
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  void _logout(BuildContext context) {
    // Реализация выхода (например, очистка данных и переход на экран входа)
    Navigator.pushReplacementNamed(context, '/login'); // Пример перехода
  }
}
