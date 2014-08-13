#include <iostream>
#include <cstdlib>

using namespace std;

int main(int argc, char *argv[]) {
  int a, b;
  while(cin >> a >> b)
    cout << a + b << endl;
  cerr << "test" << endl;
  system("echo test");
  return 0;
}
