import 'package:flutter/material.dart';
import 'package:mobile_scanner/mobile_scanner.dart';
import 'package:geolocator/geolocator.dart';
import '../../screen/web.dart';
import '../../globals/var.dart';

String latitudeData = "";
String longitudeData = "";
dynamic status = false;

// Экран со сканером QR-кодов
class QRScannerScreen extends StatefulWidget {
  const QRScannerScreen({Key? key}) : super(key: key);

  @override
  State<QRScannerScreen> createState() => _QRScannerScreenState();
}

class _QRScannerScreenState extends State<QRScannerScreen> {
  String?
      lastScannedCode; // Переменная для хранения последнего считанного QR-кода

  @override
  void initState() {
    super.initState();
    getCurrentLocation();
  }

  getCurrentLocation() async {
    final geoposition = await Geolocator.getCurrentPosition(
        desiredAccuracy: LocationAccuracy.high);

    bool servicestatus = await Geolocator.isLocationServiceEnabled();
    setState(() {
      latitudeData = geoposition.latitude.toString();
      longitudeData = geoposition.longitude.toString();
      status = servicestatus;
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Сканер QR-кодов'),
        centerTitle: true,
      ),
      body: MobileScanner(
        onDetect: (capture) {
          final List<Barcode> barcodes = capture.barcodes;

          for (final barcode in barcodes) {
            final String? code = barcode.rawValue;

            if (code != null && code != lastScannedCode) {
              lastScannedCode = code;
              debugPrint('Считанный QR-код: $code');

              // Переход на экран с WebView
              String? personid = GlobalUser.personid;
              String? person = GlobalUser.person;
              String? url =
                  "$code&personid=$personid&person=$person&latitudeData=$latitudeData&longitudeData=$longitudeData";
              Navigator.push(
                context,
                MaterialPageRoute(
                  builder: (context) => WebPageWithController(initialUrl: url),
                ),
              );

              break; // Прерываем обработку после первого найденного QR-кода
            }
          }
        },
      ),
    );
  }
}
