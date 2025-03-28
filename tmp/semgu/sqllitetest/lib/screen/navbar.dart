import '../../screen/qrcode.dart';
import '../../globals/var.dart';
import '../../screen/home.dart';
import '../../screen/web.dart';
import '../../screen/course.dart';
//import 'package:bottmappbar/qrcode.dart';
import 'package:flutter/material.dart';
import 'package:geolocator/geolocator.dart';

class Navbar extends StatefulWidget {
  const Navbar({super.key});

  @override
  State<Navbar> createState() => _NavbarState();
}

class _NavbarState extends State<Navbar> {
  int currentTab = 0; // Track the current tab
  final List<Widget> screens = [
    UserProfileScreen(),
    QRScannerScreen(),
    CourseInfoScreen(),
    WebPageWithController(
      initialUrl: '',
    ),
  ];
  final PageStorageBucket bucket = PageStorageBucket();
  Widget currentScreen = UserProfileScreen(); // Default screen is Dashboard

  @override
  Widget build(BuildContext context) {
    final screenWidth = MediaQuery.of(context).size.width / 4 - 60;
    return Scaffold(
      appBar: currentTab == 0
          ? null // No AppBar for Dashboard
          : AppBar(
              leading: IconButton(
                icon: Icon(Icons.arrow_back),
                onPressed: () {
                  setState(() {
                    currentScreen =
                        CourseInfoScreen(); // Navigate back to Dashboard
                    currentTab = 0;
                  });
                },
              ),
              title: Text(
                currentTab == 1
                    ? 'Contact'
                    : currentTab == 2
                        ? 'Chat'
                        : 'Favourite',
              ),
            ),
      body: PageStorage(bucket: bucket, child: currentScreen),
      floatingActionButton: currentTab == 0
          ? FloatingActionButton(
              onPressed: () {},
              child: Icon(Icons.add),
            )
          : null, // Hide FAB on other screens
      floatingActionButtonLocation: FloatingActionButtonLocation.centerDocked,
      bottomNavigationBar: currentTab == 0
          ? BottomAppBar(
              shape: CircularNotchedRectangle(),
              notchMargin: 10,
              child: Container(
                height: 60,
                child: Row(
                  mainAxisAlignment: MainAxisAlignment.start,
                  children: <Widget>[
                    Row(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        MaterialButton(
                          minWidth: screenWidth,
                          onPressed: () {
                            setState(() {
                              currentScreen = CourseInfoScreen();
                              currentTab = 0;
                            });
                          },
                          child: Column(
                            mainAxisAlignment: MainAxisAlignment.center,
                            children: [
                              Icon(
                                Icons.home,
                                color:
                                    currentTab == 0 ? Colors.blue : Colors.grey,
                              ),
                              Text(
                                'Басты бет',
                                style: TextStyle(
                                    color: currentTab == 0
                                        ? Colors.blue
                                        : Colors.grey),
                              )
                            ],
                          ),
                        ),
                        MaterialButton(
                          minWidth: screenWidth,
                          onPressed: () async {
                            await Geolocator.requestPermission();
                            Navigator.push(
                                context,
                                MaterialPageRoute(
                                  builder: (context) => QRScannerScreen(),
                                ));
                            /*
                            setState(() {
                              currentScreen = QRScannerScreen();
                              currentTab = 1;
                            });
                            */
                          },
                          child: Column(
                            mainAxisAlignment: MainAxisAlignment.center,
                            children: [
                              Icon(
                                Icons.browse_gallery,
                                color:
                                    currentTab == 1 ? Colors.blue : Colors.grey,
                              ),
                              Text(
                                'QR Code',
                                style: TextStyle(
                                    color: currentTab == 1
                                        ? Colors.blue
                                        : Colors.grey),
                              )
                            ],
                          ),
                        ),
                      ],
                    ),
                    SizedBox(
                      width: 44,
                    ),
                    // Right side of the bottom nav bar
                    Row(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        MaterialButton(
                          minWidth: screenWidth,
                          onPressed: () {
                            Navigator.push(
                              context,
                              MaterialPageRoute(
                                builder: (context) => WebPageWithController(
                                    initialUrl: 'https://semgu.kz'),
                              ),
                            );
                            /*
                            setState(() {
                              currentScreen = Chat();
                              currentTab = 2;
                            });
                            */
                          },
                          child: Column(
                            mainAxisAlignment: MainAxisAlignment.center,
                            children: [
                              Icon(
                                Icons.chat,
                                color:
                                    currentTab == 2 ? Colors.blue : Colors.grey,
                              ),
                              Text(
                                'Сайт',
                                style: TextStyle(
                                    color: currentTab == 2
                                        ? Colors.blue
                                        : Colors.grey),
                              )
                            ],
                          ),
                        ),
                        MaterialButton(
                          minWidth: screenWidth,
                          onPressed: () {
                            String? personid = GlobalUser.personid;
                            String? jwt = GlobalUser.jwt;
                            String? person = GlobalUser.person;
                            Navigator.push(
                              context,
                              MaterialPageRoute(
                                builder: (context) => WebPageWithController(
                                    initialUrl:
                                        'https://sdomobile.semgu.kz/index.php?personid=$personid&jwt=$jwt&person=$person'),
                              ),
                            );
                            /*setState(() {
                              currentScreen = QRScannerScreen();
                              currentTab = 3;
                            });*/
                          },
                          child: Column(
                            mainAxisAlignment: MainAxisAlignment.center,
                            children: [
                              Icon(
                                Icons.sign_language,
                                color:
                                    currentTab == 3 ? Colors.blue : Colors.grey,
                              ),
                              Text(
                                'Docs',
                                style: TextStyle(
                                    color: currentTab == 3
                                        ? Colors.blue
                                        : Colors.grey),
                              )
                            ],
                          ),
                        ),
                      ],
                    )
                  ],
                ),
              ),
            )
          : null, // Hide BottomAppBar on non-home screens
    );
  }
}
