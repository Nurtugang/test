import 'package:path/path.dart' as p;
import 'package:sqflite/sqflite.dart';

class DatabaseHelper {
  static final DatabaseHelper _instance = DatabaseHelper._internal();
  static Database? _database;

  factory DatabaseHelper() {
    return _instance;
  }

  DatabaseHelper._internal();

  Future<void> initDatabase() async {
    _database ??= await _initDatabase();
  }

  Future<Database> _initDatabase() async {
    String dbPath = await getDatabasesPath();
    String path = p.join(dbPath, 'users.db');
    return await openDatabase(
      path,
      version: 1,
      onCreate: (db, version) async {
        await db.execute('''
          CREATE TABLE users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            lastname TEXT,
            firstname TEXT,
            patronymic TEXT,
            person text,
            personid text,
            jwt text
          )
        ''');
      },
    );
  }

  Future<void> deleteUserDatabase() async {
    // Получаем путь к базе данных
    final dbPath = await getDatabasesPath();
    final path = p.join(dbPath, 'users.db');

    // Удаляем базу данных, если она существует
    try {
      await deleteDatabase(path);
      //print('База данных users.db успешно удалена.');
    } catch (e) {
      //print('Ошибка при удалении базы данных: $e');
    }
  }

  Future<Map<String, dynamic>?> getUser() async {
    final db = await _database!;
    List<Map<String, dynamic>> users = await db.query('users');
    if (users.isNotEmpty) {
      return users.first;
    }
    return null;
  }

  Future<int> insertUser(Map<String, dynamic> user) async {
    final db = await _database!;
    return await db.insert('users', user);
  }
}
