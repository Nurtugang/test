import 'package:flutter/material.dart';
import 'package:webview_flutter/webview_flutter.dart';
import 'package:geolocator/geolocator.dart';
import 'dart:convert'; // Для работы с JSON

import '../../screen/qrcode.dart';
import '../../globals/var.dart';
import '../../screen/web.dart';

class WebViewExample extends StatefulWidget {
  @override
  _WebViewExampleState createState() => _WebViewExampleState();
}

class _WebViewExampleState extends State<WebViewExample> {
  late final WebViewController _webViewController;
  var Url = GlobalUser.persondata['startpage'];
  @override
  void initState() {
    super.initState();

    _webViewController = WebViewController()
      ..setJavaScriptMode(JavaScriptMode.unrestricted)
      ..setNavigationDelegate(
        NavigationDelegate(
          onNavigationRequest: (request) {
            if (request.url.startsWith('https://')) {
              return NavigationDecision.navigate;
            }
            return NavigationDecision.prevent;
          },
        ),
      )
      ..addJavaScriptChannel(
        "FlutterChannel",
        onMessageReceived: (JavaScriptMessage message) async {
          switch (message.message) {
            case "QRCode":
              await Geolocator.requestPermission();
              Navigator.push(
                  context,
                  MaterialPageRoute(
                    builder: (context) => QRScannerScreen(),
                  ));
              break;
            default:
              int index = message.message.indexOf('{');
              if (index != -1) {
                String part1 = message.message.substring(0, index); // До '{'
                String part2 =
                    message.message.substring(index); // С '{' и дальше
                switch (part1) {
                  case "JSON":
                    final jsonstring = jsonDecode(part2);
                    switch (jsonstring) {
                      case "SETTINGS":
                        break;
                    }
                    break;
                  default:
                    break;
                }
              } else {
                Navigator.push(
                    context,
                    MaterialPageRoute(
                      builder: (context) =>
                          WebPageWithController(initialUrl: message.message),
                    ));
              }
          }
          // Получаем сообщение от WebView и показываем его в SnackBar
          //ScaffoldMessenger.of(context).showSnackBar(
          //  SnackBar(content: Text("Сообщение из WebView: ${message.message}")),
          //);
        },
      )
      ..loadRequest(Uri.parse(
        Url,
      ));
  }

  /// HTML-код, который загружается в WebView
  String _getHtmlContent() {
    return """
      <!DOCTYPE html>
      <html>
      <head>
        <title>WebView Test</title>
        <script>
          function changeBackgroundColor(color) {
            document.body.style.backgroundColor = color;
          }

          function sendMessageToFlutter() {
            if (window.FlutterChannel) {
              window.FlutterChannel.postMessage("Привет, Flutter! Сообщение из WebView.");
            }
          }
        </script>
      </head>
      <body style="font-family: Arial, sans-serif; text-align: center; padding: 20px;">
        <h2>WebView Взаимодействие</h2>
        <p>Нажмите кнопку ниже, чтобы отправить сообщение в Flutter</p>
        <button onclick="sendMessageToFlutter()" style="padding: 10px 20px; font-size: 16px;">
          Отправить сообщение в Flutter
        </button>
      </body>
      </html>
    """;
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      /*appBar: AppBar(
        title: Text('WebView с взаимодействием'),
        actions: [
          IconButton(
            icon: Icon(Icons.refresh),
            onPressed: () => _webViewController.reload(),
          ),
        ],
      ),*/
      body: Column(
        children: [
          Expanded(child: WebViewWidget(controller: _webViewController)),
          SizedBox(height: 10),
          /*ElevatedButton(
            onPressed: () {
              // Flutter отправляет команду WebView изменить цвет фона
              _webViewController
                  .runJavaScript("changeBackgroundColor('lightblue');");
            },
            child: Text("Изменить фон"),
          ),*/
          //SizedBox(height: 20),
        ],
      ),
    );
  }
}
