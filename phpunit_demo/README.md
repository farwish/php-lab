# test demo

StackTest.php  
> @depends testEmpty  
> assertEmpty()   
> assertNotEmpty()  
> assertEquals()  

MultipleDependenciesTest.php  
> @depends testProducerFirst  
> @depends testProducerSecond  

DataProvider.php  
> @dataProvider provider  

DependencyAndDataProviderComboTest.php  
> @dataProvider provider  
> @depends testProducerFirst  
> @depends testProducerSecond   
> assertTrue()  

ExceptionTest.php  
> @expectedException  
> expectException(Exception::class)  

ExpectedErrorTest.php   

ErrorSuppressionTest.php  

OutputTest.php  
> expectedOutputString()

