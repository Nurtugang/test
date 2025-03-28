import 'package:flutter/material.dart';
import 'package:webview_flutter/webview_flutter.dart';

// Экран с WebView
class WebPageWithController extends StatefulWidget {
  final String initialUrl;

  WebPageWithController({required this.initialUrl});

  @override
  _WebPageWithControllerState createState() => _WebPageWithControllerState();
}

class _WebPageWithControllerState extends State<WebPageWithController> {
  late final WebViewController _webViewController;

  @override
  void initState() {
    super.initState();

    // Инициализация WebViewController
    _webViewController = WebViewController();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('WebView with Controller'),
        actions: [
          IconButton(
            icon: Icon(Icons.refresh),
            onPressed: () {
              // Обновление текущей страницы
              _webViewController.reload();
            },
          ),
          IconButton(
            icon: Icon(Icons.arrow_back),
            onPressed: () async {
              // Переход назад, если возможно
              if (await _webViewController.canGoBack()) {
                _webViewController.goBack();
              }
            },
          ),
          IconButton(
            icon: Icon(Icons.arrow_forward),
            onPressed: () async {
              // Переход вперёд, если возможно
              if (await _webViewController.canGoForward()) {
                _webViewController.goForward();
              }
            },
          ),
        ],
      ),
      body: WebViewWidget(
        controller: _webViewController
          ..setJavaScriptMode(JavaScriptMode.unrestricted)
          ..setNavigationDelegate(
            NavigationDelegate(
              onNavigationRequest: (request) {
                // Проверка перед загрузкой страницы
                if (request.url.startsWith('https://')) {
                  return NavigationDecision.navigate;
                }
                return NavigationDecision.prevent;
              },
              onPageStarted: (url) {
                //print('Page started loading: $url');
              },
              onPageFinished: (url) {
                //print('Page finished loading: $url');
              },
            ),
          )
          ..loadRequest(Uri.parse(widget.initialUrl)),
      ),
    );
  }
}
